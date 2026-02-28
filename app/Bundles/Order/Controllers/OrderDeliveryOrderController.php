<?php

declare(strict_types=1);

namespace App\Bundles\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Order\Entities\OrderDeliveryOrderEntity;
use App\Bundles\Order\Requests\OrderDeliveryOrder\OrderDeliveryOrderCreateRequest;
use App\Bundles\Order\Requests\OrderDeliveryOrder\OrderDeliveryOrderDestroyRequest;
use App\Bundles\Order\Requests\OrderDeliveryOrder\OrderDeliveryOrderQueryRequest;
use App\Bundles\Order\Requests\OrderDeliveryOrder\OrderDeliveryOrderUpdateRequest;
use App\Bundles\Order\Responses\OrderDeliveryOrder\OrderDeliveryOrderDestroyResponse;
use App\Bundles\Order\Responses\OrderDeliveryOrder\OrderDeliveryOrderQueryResponse;
use App\Bundles\Order\Responses\OrderDeliveryOrder\OrderDeliveryOrderResponse;
use App\Bundles\Order\Services\OrderDeliveryOrderBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderDeliveryOrderController extends BaseController
{
    #[OA\Post(path: '/orderDeliveryOrder/query', summary: '查询发货订单列表接口', security: [['bearerAuth' => []]], tags: ['发货订单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderDeliveryOrderQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderDeliveryOrderQueryResponse::class))]
    public function query(OrderDeliveryOrderQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderDeliveryOrderQueryRequest::getOrderId])) {
                $condition[] = [OrderDeliveryOrderEntity::getOrderId, '=', $requestData[OrderDeliveryOrderQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderDeliveryOrderQueryRequest::getUserId])) {
                $condition[] = [OrderDeliveryOrderEntity::getUserId, '=', $requestData[OrderDeliveryOrderQueryRequest::getUserId]];
            }
            if (isset($requestData[OrderDeliveryOrderQueryRequest::getDeliveryId])) {
                $condition[] = [OrderDeliveryOrderEntity::getDeliveryId, '=', $requestData[OrderDeliveryOrderQueryRequest::getDeliveryId]];
            }

            $orderDeliveryOrderBundleService = new OrderDeliveryOrderBundleService;
            $result = $orderDeliveryOrderBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderDeliveryOrderResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new OrderDeliveryOrderQueryResponse($result);
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

    #[OA\Post(path: '/orderDeliveryOrder/store', summary: '新增发货订单接口', security: [['bearerAuth' => []]], tags: ['发货订单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderDeliveryOrderCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderDeliveryOrderResponse::class))]
    public function store(OrderDeliveryOrderCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new OrderDeliveryOrderEntity($requestData);

            $orderDeliveryOrderBundleService = new OrderDeliveryOrderBundleService;
            if ($orderDeliveryOrderBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderDeliveryOrder/show', summary: '获取发货订单详情接口', security: [['bearerAuth' => []]], tags: ['发货订单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderDeliveryOrderResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $orderDeliveryOrderBundleService = new OrderDeliveryOrderBundleService;
            $orderDeliveryOrder = $orderDeliveryOrderBundleService->getOneById($id);
            if (empty($orderDeliveryOrder)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new OrderDeliveryOrderResponse($orderDeliveryOrder);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderDeliveryOrder/update', summary: '更新发货订单接口', security: [['bearerAuth' => []]], tags: ['发货订单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderDeliveryOrderUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderDeliveryOrderResponse::class))]
    public function update(OrderDeliveryOrderUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderDeliveryOrderBundleService = new OrderDeliveryOrderBundleService;
            $orderDeliveryOrder = $orderDeliveryOrderBundleService->getOneById($id);
            if (empty($orderDeliveryOrder)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new OrderDeliveryOrderEntity($requestData);

            $orderDeliveryOrderBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/orderDeliveryOrder/destroy', summary: '删除发货订单接口', security: [['bearerAuth' => []]], tags: ['发货订单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderDeliveryOrderDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderDeliveryOrderDestroyResponse::class))]
    public function destroy(OrderDeliveryOrderDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $orderDeliveryOrderBundleService = new OrderDeliveryOrderBundleService;
            if ($orderDeliveryOrderBundleService->removeByIds($requestData['ids'])) {
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
