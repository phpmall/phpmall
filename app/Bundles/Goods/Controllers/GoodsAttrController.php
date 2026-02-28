<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsAttrEntity;
use App\Bundles\Goods\Requests\GoodsAttr\GoodsAttrCreateRequest;
use App\Bundles\Goods\Requests\GoodsAttr\GoodsAttrDestroyRequest;
use App\Bundles\Goods\Requests\GoodsAttr\GoodsAttrQueryRequest;
use App\Bundles\Goods\Requests\GoodsAttr\GoodsAttrUpdateRequest;
use App\Bundles\Goods\Responses\GoodsAttr\GoodsAttrDestroyResponse;
use App\Bundles\Goods\Responses\GoodsAttr\GoodsAttrQueryResponse;
use App\Bundles\Goods\Responses\GoodsAttr\GoodsAttrResponse;
use App\Bundles\Goods\Services\GoodsAttrBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsAttrController extends BaseController
{
    #[OA\Post(path: '/goodsAttr/query', summary: '查询商品属性列表接口', security: [['bearerAuth' => []]], tags: ['商品属性模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsAttrQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsAttrQueryResponse::class))]
    public function query(GoodsAttrQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsAttrQueryRequest::getAttrId])) {
                $condition[] = [GoodsAttrEntity::getAttrId, '=', $requestData[GoodsAttrQueryRequest::getAttrId]];
            }
            if (isset($requestData[GoodsAttrQueryRequest::getGoodsId])) {
                $condition[] = [GoodsAttrEntity::getGoodsId, '=', $requestData[GoodsAttrQueryRequest::getGoodsId]];
            }
            if (isset($requestData[GoodsAttrQueryRequest::getGoodsAttrId])) {
                $condition[] = [GoodsAttrEntity::getGoodsAttrId, '=', $requestData[GoodsAttrQueryRequest::getGoodsAttrId]];
            }

            $goodsAttrBundleService = new GoodsAttrBundleService;
            $result = $goodsAttrBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsAttrResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsAttrQueryResponse($result);
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

    #[OA\Post(path: '/goodsAttr/store', summary: '新增商品属性接口', security: [['bearerAuth' => []]], tags: ['商品属性模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsAttrCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsAttrResponse::class))]
    public function store(GoodsAttrCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsAttrEntity($requestData);

            $goodsAttrBundleService = new GoodsAttrBundleService;
            if ($goodsAttrBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsAttr/show', summary: '获取商品属性详情接口', security: [['bearerAuth' => []]], tags: ['商品属性模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsAttrResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsAttrBundleService = new GoodsAttrBundleService;
            $goodsAttr = $goodsAttrBundleService->getOneById($id);
            if (empty($goodsAttr)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsAttrResponse($goodsAttr);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsAttr/update', summary: '更新商品属性接口', security: [['bearerAuth' => []]], tags: ['商品属性模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsAttrUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsAttrResponse::class))]
    public function update(GoodsAttrUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsAttrBundleService = new GoodsAttrBundleService;
            $goodsAttr = $goodsAttrBundleService->getOneById($id);
            if (empty($goodsAttr)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsAttrEntity($requestData);

            $goodsAttrBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsAttr/destroy', summary: '删除商品属性接口', security: [['bearerAuth' => []]], tags: ['商品属性模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsAttrDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsAttrDestroyResponse::class))]
    public function destroy(GoodsAttrDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsAttrBundleService = new GoodsAttrBundleService;
            if ($goodsAttrBundleService->removeByIds($requestData['ids'])) {
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
