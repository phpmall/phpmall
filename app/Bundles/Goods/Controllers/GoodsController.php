<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsEntity;
use App\Bundles\Goods\Requests\Goods\GoodsCreateRequest;
use App\Bundles\Goods\Requests\Goods\GoodsDestroyRequest;
use App\Bundles\Goods\Requests\Goods\GoodsQueryRequest;
use App\Bundles\Goods\Requests\Goods\GoodsUpdateRequest;
use App\Bundles\Goods\Responses\Goods\GoodsDestroyResponse;
use App\Bundles\Goods\Responses\Goods\GoodsQueryResponse;
use App\Bundles\Goods\Responses\Goods\GoodsResponse;
use App\Bundles\Goods\Services\GoodsBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsController extends BaseController
{
    #[OA\Post(path: '/goods/query', summary: '查询商品列表接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsQueryResponse::class))]
    public function query(GoodsQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsQueryRequest::getBrandId])) {
                $condition[] = [GoodsEntity::getBrandId, '=', $requestData[GoodsQueryRequest::getBrandId]];
            }
            if (isset($requestData[GoodsQueryRequest::getCatId])) {
                $condition[] = [GoodsEntity::getCatId, '=', $requestData[GoodsQueryRequest::getCatId]];
            }
            if (isset($requestData[GoodsQueryRequest::getIsOnSale])) {
                $condition[] = [GoodsEntity::getIsOnSale, '=', $requestData[GoodsQueryRequest::getIsOnSale]];
            }
            if (isset($requestData[GoodsQueryRequest::getGoodsNumber])) {
                $condition[] = [GoodsEntity::getGoodsNumber, '=', $requestData[GoodsQueryRequest::getGoodsNumber]];
            }
            if (isset($requestData[GoodsQueryRequest::getGoodsSn])) {
                $condition[] = [GoodsEntity::getGoodsSn, '=', $requestData[GoodsQueryRequest::getGoodsSn]];
            }
            if (isset($requestData[GoodsQueryRequest::getGoodsWeight])) {
                $condition[] = [GoodsEntity::getGoodsWeight, '=', $requestData[GoodsQueryRequest::getGoodsWeight]];
            }
            if (isset($requestData[GoodsQueryRequest::getCatId])) {
                $condition[] = [GoodsEntity::getCatId, '=', $requestData[GoodsQueryRequest::getCatId]];
            }
            if (isset($requestData[GoodsQueryRequest::getLastUpdate])) {
                $condition[] = [GoodsEntity::getLastUpdate, '=', $requestData[GoodsQueryRequest::getLastUpdate]];
            }
            if (isset($requestData[GoodsQueryRequest::getPromoteEndDate])) {
                $condition[] = [GoodsEntity::getPromoteEndDate, '=', $requestData[GoodsQueryRequest::getPromoteEndDate]];
            }
            if (isset($requestData[GoodsQueryRequest::getPromoteStartDate])) {
                $condition[] = [GoodsEntity::getPromoteStartDate, '=', $requestData[GoodsQueryRequest::getPromoteStartDate]];
            }
            if (isset($requestData[GoodsQueryRequest::getSortOrder])) {
                $condition[] = [GoodsEntity::getSortOrder, '=', $requestData[GoodsQueryRequest::getSortOrder]];
            }
            if (isset($requestData[GoodsQueryRequest::getGoodsId])) {
                $condition[] = [GoodsEntity::getGoodsId, '=', $requestData[GoodsQueryRequest::getGoodsId]];
            }

            $goodsBundleService = new GoodsBundleService;
            $result = $goodsBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsQueryResponse($result);
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

    #[OA\Post(path: '/goods/store', summary: '新增商品接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsResponse::class))]
    public function store(GoodsCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsEntity($requestData);

            $goodsBundleService = new GoodsBundleService;
            if ($goodsBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goods/show', summary: '获取商品详情接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsBundleService = new GoodsBundleService;
            $goods = $goodsBundleService->getOneById($id);
            if (empty($goods)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsResponse($goods);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goods/update', summary: '更新商品接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsResponse::class))]
    public function update(GoodsUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsBundleService = new GoodsBundleService;
            $goods = $goodsBundleService->getOneById($id);
            if (empty($goods)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsEntity($requestData);

            $goodsBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goods/destroy', summary: '删除商品接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsDestroyResponse::class))]
    public function destroy(GoodsDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsBundleService = new GoodsBundleService;
            if ($goodsBundleService->removeByIds($requestData['ids'])) {
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
