<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsCategoryEntity;
use App\Bundles\Goods\Requests\GoodsCategory\GoodsCategoryCreateRequest;
use App\Bundles\Goods\Requests\GoodsCategory\GoodsCategoryDestroyRequest;
use App\Bundles\Goods\Requests\GoodsCategory\GoodsCategoryQueryRequest;
use App\Bundles\Goods\Requests\GoodsCategory\GoodsCategoryUpdateRequest;
use App\Bundles\Goods\Responses\GoodsCategory\GoodsCategoryDestroyResponse;
use App\Bundles\Goods\Responses\GoodsCategory\GoodsCategoryQueryResponse;
use App\Bundles\Goods\Responses\GoodsCategory\GoodsCategoryResponse;
use App\Bundles\Goods\Services\GoodsCategoryBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsCategoryController extends BaseController
{
    #[OA\Post(path: '/goodsCategory/query', summary: '查询商品分类列表接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCategoryQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCategoryQueryResponse::class))]
    public function query(GoodsCategoryQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsCategoryQueryRequest::getParentId])) {
                $condition[] = [GoodsCategoryEntity::getParentId, '=', $requestData[GoodsCategoryQueryRequest::getParentId]];
            }
            if (isset($requestData[GoodsCategoryQueryRequest::getSortOrder])) {
                $condition[] = [GoodsCategoryEntity::getSortOrder, '=', $requestData[GoodsCategoryQueryRequest::getSortOrder]];
            }
            if (isset($requestData[GoodsCategoryQueryRequest::getCatId])) {
                $condition[] = [GoodsCategoryEntity::getCatId, '=', $requestData[GoodsCategoryQueryRequest::getCatId]];
            }

            $goodsCategoryBundleService = new GoodsCategoryBundleService;
            $result = $goodsCategoryBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsCategoryResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsCategoryQueryResponse($result);
            $response->setFirstPageUrl('');
            $response->setLastPageUrl('');
            $response->setLinks([]);
            $response->setPath('');

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/goodsCategory/store', summary: '新增商品分类接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCategoryCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCategoryResponse::class))]
    public function store(GoodsCategoryCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsCategoryEntity($requestData);

            $goodsCategoryBundleService = new GoodsCategoryBundleService;
            if ($goodsCategoryBundleService->save($input->toEntity())) {
                DB::commit();

                return $this->success();
            }

            throw new BusinessException(BusinessEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/goodsCategory/show', summary: '获取商品分类详情接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCategoryResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsCategoryBundleService = new GoodsCategoryBundleService;
            $goodsCategory = $goodsCategoryBundleService->getOneById($id);
            if (empty($goodsCategory)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsCategoryResponse($goodsCategory);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsCategory/update', summary: '更新商品分类接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCategoryUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCategoryResponse::class))]
    public function update(GoodsCategoryUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsCategoryBundleService = new GoodsCategoryBundleService;
            $goodsCategory = $goodsCategoryBundleService->getOneById($id);
            if (empty($goodsCategory)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsCategoryEntity($requestData);

            $goodsCategoryBundleService->updateById($input->toEntity(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/goodsCategory/destroy', summary: '删除商品分类接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCategoryDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCategoryDestroyResponse::class))]
    public function destroy(GoodsCategoryDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsCategoryBundleService = new GoodsCategoryBundleService;
            if ($goodsCategoryBundleService->removeByIds($requestData['ids'])) {
                DB::commit();

                return $this->success();
            }

            throw new BusinessException(BusinessEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::DESTROY_ERROR);
        }
    }
}
