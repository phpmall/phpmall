<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsCatEntity;
use App\Bundles\Goods\Requests\GoodsCat\GoodsCatCreateRequest;
use App\Bundles\Goods\Requests\GoodsCat\GoodsCatDestroyRequest;
use App\Bundles\Goods\Requests\GoodsCat\GoodsCatQueryRequest;
use App\Bundles\Goods\Requests\GoodsCat\GoodsCatUpdateRequest;
use App\Bundles\Goods\Responses\GoodsCat\GoodsCatDestroyResponse;
use App\Bundles\Goods\Responses\GoodsCat\GoodsCatQueryResponse;
use App\Bundles\Goods\Responses\GoodsCat\GoodsCatResponse;
use App\Bundles\Goods\Services\GoodsCatBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsCatController extends BaseController
{
    #[OA\Post(path: '/goodsCat/query', summary: '查询商品分类关联列表接口', security: [['bearerAuth' => []]], tags: ['商品分类关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCatQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCatQueryResponse::class))]
    public function query(GoodsCatQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsCatQueryRequest::getCatId])) {
                $condition[] = [GoodsCatEntity::getCatId, '=', $requestData[GoodsCatQueryRequest::getCatId]];
            }
            if (isset($requestData[GoodsCatQueryRequest::getId])) {
                $condition[] = [GoodsCatEntity::getId, '=', $requestData[GoodsCatQueryRequest::getId]];
            }

            $goodsCatBundleService = new GoodsCatBundleService;
            $result = $goodsCatBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsCatResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsCatQueryResponse($result);
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

    #[OA\Post(path: '/goodsCat/store', summary: '新增商品分类关联接口', security: [['bearerAuth' => []]], tags: ['商品分类关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCatCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCatResponse::class))]
    public function store(GoodsCatCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsCatEntity($requestData);

            $goodsCatBundleService = new GoodsCatBundleService;
            if ($goodsCatBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsCat/show', summary: '获取商品分类关联详情接口', security: [['bearerAuth' => []]], tags: ['商品分类关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCatResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsCatBundleService = new GoodsCatBundleService;
            $goodsCat = $goodsCatBundleService->getOneById($id);
            if (empty($goodsCat)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsCatResponse($goodsCat);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsCat/update', summary: '更新商品分类关联接口', security: [['bearerAuth' => []]], tags: ['商品分类关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCatUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCatResponse::class))]
    public function update(GoodsCatUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsCatBundleService = new GoodsCatBundleService;
            $goodsCat = $goodsCatBundleService->getOneById($id);
            if (empty($goodsCat)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsCatEntity($requestData);

            $goodsCatBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsCat/destroy', summary: '删除商品分类关联接口', security: [['bearerAuth' => []]], tags: ['商品分类关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCatDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCatDestroyResponse::class))]
    public function destroy(GoodsCatDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsCatBundleService = new GoodsCatBundleService;
            if ($goodsCatBundleService->removeByIds($requestData['ids'])) {
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
