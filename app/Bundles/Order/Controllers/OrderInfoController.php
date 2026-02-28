<?php

declare(strict_types=1);

namespace App\Bundles\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Order\Entities\OrderInfoEntity;
use App\Bundles\Order\Requests\OrderInfo\OrderInfoCreateRequest;
use App\Bundles\Order\Requests\OrderInfo\OrderInfoDestroyRequest;
use App\Bundles\Order\Requests\OrderInfo\OrderInfoQueryRequest;
use App\Bundles\Order\Requests\OrderInfo\OrderInfoUpdateRequest;
use App\Bundles\Order\Responses\OrderInfo\OrderInfoDestroyResponse;
use App\Bundles\Order\Responses\OrderInfo\OrderInfoQueryResponse;
use App\Bundles\Order\Responses\OrderInfo\OrderInfoResponse;
use App\Bundles\Order\Services\OrderInfoBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderInfoController extends BaseController
{
    #[OA\Post(path: '/orderInfo/query', summary: '查询订单信息列表接口', security: [['bearerAuth' => []]], tags: ['订单信息模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderInfoQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderInfoQueryResponse::class))]
    public function query(OrderInfoQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderInfoQueryRequest::getAgencyId])) {
                $condition[] = [OrderInfoEntity::getAgencyId, '=', $requestData[OrderInfoQueryRequest::getAgencyId]];
            }
            if (isset($requestData[OrderInfoQueryRequest::getExtensionId])) {
                $condition[] = [OrderInfoEntity::getExtensionId, '=', $requestData[OrderInfoQueryRequest::getExtensionId]];
            }
            if (isset($requestData[OrderInfoQueryRequest::getOrderSn])) {
                $condition[] = [OrderInfoEntity::getOrderSn, '=', $requestData[OrderInfoQueryRequest::getOrderSn]];
            }
            if (isset($requestData[OrderInfoQueryRequest::getOrderStatus])) {
                $condition[] = [OrderInfoEntity::getOrderStatus, '=', $requestData[OrderInfoQueryRequest::getOrderStatus]];
            }
            if (isset($requestData[OrderInfoQueryRequest::getPayId])) {
                $condition[] = [OrderInfoEntity::getPayId, '=', $requestData[OrderInfoQueryRequest::getPayId]];
            }
            if (isset($requestData[OrderInfoQueryRequest::getPayStatus])) {
                $condition[] = [OrderInfoEntity::getPayStatus, '=', $requestData[OrderInfoQueryRequest::getPayStatus]];
            }
            if (isset($requestData[OrderInfoQueryRequest::getShippingId])) {
                $condition[] = [OrderInfoEntity::getShippingId, '=', $requestData[OrderInfoQueryRequest::getShippingId]];
            }
            if (isset($requestData[OrderInfoQueryRequest::getShippingStatus])) {
                $condition[] = [OrderInfoEntity::getShippingStatus, '=', $requestData[OrderInfoQueryRequest::getShippingStatus]];
            }
            if (isset($requestData[OrderInfoQueryRequest::getUserId])) {
                $condition[] = [OrderInfoEntity::getUserId, '=', $requestData[OrderInfoQueryRequest::getUserId]];
            }
            if (isset($requestData[OrderInfoQueryRequest::getOrderStatus])) {
                $condition[] = [OrderInfoEntity::getOrderStatus, '=', $requestData[OrderInfoQueryRequest::getOrderStatus]];
            }
            if (isset($requestData[OrderInfoQueryRequest::getOrderId])) {
                $condition[] = [OrderInfoEntity::getOrderId, '=', $requestData[OrderInfoQueryRequest::getOrderId]];
            }

            $orderInfoBundleService = new OrderInfoBundleService;
            $result = $orderInfoBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderInfoResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new OrderInfoQueryResponse($result);
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

    #[OA\Post(path: '/orderInfo/store', summary: '新增订单信息接口', security: [['bearerAuth' => []]], tags: ['订单信息模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderInfoCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderInfoResponse::class))]
    public function store(OrderInfoCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new OrderInfoEntity($requestData);

            $orderInfoBundleService = new OrderInfoBundleService;
            if ($orderInfoBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderInfo/show', summary: '获取订单信息详情接口', security: [['bearerAuth' => []]], tags: ['订单信息模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderInfoResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $orderInfoBundleService = new OrderInfoBundleService;
            $orderInfo = $orderInfoBundleService->getOneById($id);
            if (empty($orderInfo)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new OrderInfoResponse($orderInfo);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderInfo/update', summary: '更新订单信息接口', security: [['bearerAuth' => []]], tags: ['订单信息模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderInfoUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderInfoResponse::class))]
    public function update(OrderInfoUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderInfoBundleService = new OrderInfoBundleService;
            $orderInfo = $orderInfoBundleService->getOneById($id);
            if (empty($orderInfo)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new OrderInfoEntity($requestData);

            $orderInfoBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/orderInfo/destroy', summary: '删除订单信息接口', security: [['bearerAuth' => []]], tags: ['订单信息模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderInfoDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderInfoDestroyResponse::class))]
    public function destroy(OrderInfoDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $orderInfoBundleService = new OrderInfoBundleService;
            if ($orderInfoBundleService->removeByIds($requestData['ids'])) {
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
