<?php

declare(strict_types=1);

namespace App\Bundles\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Order\Entities\OrderGoodsEntity;
use App\Bundles\Order\Requests\OrderGoods\OrderGoodsCreateRequest;
use App\Bundles\Order\Requests\OrderGoods\OrderGoodsDestroyRequest;
use App\Bundles\Order\Requests\OrderGoods\OrderGoodsQueryRequest;
use App\Bundles\Order\Requests\OrderGoods\OrderGoodsUpdateRequest;
use App\Bundles\Order\Responses\OrderGoods\OrderGoodsDestroyResponse;
use App\Bundles\Order\Responses\OrderGoods\OrderGoodsQueryResponse;
use App\Bundles\Order\Responses\OrderGoods\OrderGoodsResponse;
use App\Bundles\Order\Services\OrderGoodsBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderGoodsController extends BaseController
{
    #[OA\Post(path: '/orderGoods/query', summary: '查询订单商品列表接口', security: [['bearerAuth' => []]], tags: ['订单商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderGoodsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderGoodsQueryResponse::class))]
    public function query(OrderGoodsQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderGoodsQueryRequest::getGoodsId])) {
                $condition[] = [OrderGoodsEntity::getGoodsId, '=', $requestData[OrderGoodsQueryRequest::getGoodsId]];
            }
            if (isset($requestData[OrderGoodsQueryRequest::getOrderId])) {
                $condition[] = [OrderGoodsEntity::getOrderId, '=', $requestData[OrderGoodsQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderGoodsQueryRequest::getRecId])) {
                $condition[] = [OrderGoodsEntity::getRecId, '=', $requestData[OrderGoodsQueryRequest::getRecId]];
            }

            $orderGoodsBundleService = new OrderGoodsBundleService;
            $result = $orderGoodsBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderGoodsResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new OrderGoodsQueryResponse($result);
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

    #[OA\Post(path: '/orderGoods/store', summary: '新增订单商品接口', security: [['bearerAuth' => []]], tags: ['订单商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderGoodsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderGoodsResponse::class))]
    public function store(OrderGoodsCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new OrderGoodsEntity($requestData);

            $orderGoodsBundleService = new OrderGoodsBundleService;
            if ($orderGoodsBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderGoods/show', summary: '获取订单商品详情接口', security: [['bearerAuth' => []]], tags: ['订单商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderGoodsResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $orderGoodsBundleService = new OrderGoodsBundleService;
            $orderGoods = $orderGoodsBundleService->getOneById($id);
            if (empty($orderGoods)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new OrderGoodsResponse($orderGoods);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderGoods/update', summary: '更新订单商品接口', security: [['bearerAuth' => []]], tags: ['订单商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderGoodsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderGoodsResponse::class))]
    public function update(OrderGoodsUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderGoodsBundleService = new OrderGoodsBundleService;
            $orderGoods = $orderGoodsBundleService->getOneById($id);
            if (empty($orderGoods)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new OrderGoodsEntity($requestData);

            $orderGoodsBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/orderGoods/destroy', summary: '删除订单商品接口', security: [['bearerAuth' => []]], tags: ['订单商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderGoodsDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderGoodsDestroyResponse::class))]
    public function destroy(OrderGoodsDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $orderGoodsBundleService = new OrderGoodsBundleService;
            if ($orderGoodsBundleService->removeByIds($requestData['ids'])) {
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
