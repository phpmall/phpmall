<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsTypeAttributeEntity;
use App\Bundles\Goods\Requests\GoodsTypeAttribute\GoodsTypeAttributeCreateRequest;
use App\Bundles\Goods\Requests\GoodsTypeAttribute\GoodsTypeAttributeDestroyRequest;
use App\Bundles\Goods\Requests\GoodsTypeAttribute\GoodsTypeAttributeQueryRequest;
use App\Bundles\Goods\Requests\GoodsTypeAttribute\GoodsTypeAttributeUpdateRequest;
use App\Bundles\Goods\Responses\GoodsTypeAttribute\GoodsTypeAttributeDestroyResponse;
use App\Bundles\Goods\Responses\GoodsTypeAttribute\GoodsTypeAttributeQueryResponse;
use App\Bundles\Goods\Responses\GoodsTypeAttribute\GoodsTypeAttributeResponse;
use App\Bundles\Goods\Services\GoodsTypeAttributeBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsTypeAttributeController extends BaseController
{
    #[OA\Post(path: '/goodsTypeAttribute/query', summary: '查询商品类型属性列表接口', security: [['bearerAuth' => []]], tags: ['商品类型属性模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsTypeAttributeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsTypeAttributeQueryResponse::class))]
    public function query(GoodsTypeAttributeQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsTypeAttributeQueryRequest::getCatId])) {
                $condition[] = [GoodsTypeAttributeEntity::getCatId, '=', $requestData[GoodsTypeAttributeQueryRequest::getCatId]];
            }
            if (isset($requestData[GoodsTypeAttributeQueryRequest::getAttrId])) {
                $condition[] = [GoodsTypeAttributeEntity::getAttrId, '=', $requestData[GoodsTypeAttributeQueryRequest::getAttrId]];
            }

            $goodsTypeAttributeBundleService = new GoodsTypeAttributeBundleService;
            $result = $goodsTypeAttributeBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsTypeAttributeResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsTypeAttributeQueryResponse($result);
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

    #[OA\Post(path: '/goodsTypeAttribute/store', summary: '新增商品类型属性接口', security: [['bearerAuth' => []]], tags: ['商品类型属性模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsTypeAttributeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsTypeAttributeResponse::class))]
    public function store(GoodsTypeAttributeCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsTypeAttributeEntity($requestData);

            $goodsTypeAttributeBundleService = new GoodsTypeAttributeBundleService;
            if ($goodsTypeAttributeBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsTypeAttribute/show', summary: '获取商品类型属性详情接口', security: [['bearerAuth' => []]], tags: ['商品类型属性模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsTypeAttributeResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsTypeAttributeBundleService = new GoodsTypeAttributeBundleService;
            $goodsTypeAttribute = $goodsTypeAttributeBundleService->getOneById($id);
            if (empty($goodsTypeAttribute)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsTypeAttributeResponse($goodsTypeAttribute);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsTypeAttribute/update', summary: '更新商品类型属性接口', security: [['bearerAuth' => []]], tags: ['商品类型属性模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsTypeAttributeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsTypeAttributeResponse::class))]
    public function update(GoodsTypeAttributeUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsTypeAttributeBundleService = new GoodsTypeAttributeBundleService;
            $goodsTypeAttribute = $goodsTypeAttributeBundleService->getOneById($id);
            if (empty($goodsTypeAttribute)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsTypeAttributeEntity($requestData);

            $goodsTypeAttributeBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsTypeAttribute/destroy', summary: '删除商品类型属性接口', security: [['bearerAuth' => []]], tags: ['商品类型属性模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsTypeAttributeDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsTypeAttributeDestroyResponse::class))]
    public function destroy(GoodsTypeAttributeDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsTypeAttributeBundleService = new GoodsTypeAttributeBundleService;
            if ($goodsTypeAttributeBundleService->removeByIds($requestData['ids'])) {
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
