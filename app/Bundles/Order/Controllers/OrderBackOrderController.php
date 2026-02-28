<?php

declare(strict_types=1);

namespace App\Bundles\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Order\Entities\OrderBackOrderEntity;
use App\Bundles\Order\Requests\OrderBackOrder\OrderBackOrderCreateRequest;
use App\Bundles\Order\Requests\OrderBackOrder\OrderBackOrderDestroyRequest;
use App\Bundles\Order\Requests\OrderBackOrder\OrderBackOrderQueryRequest;
use App\Bundles\Order\Requests\OrderBackOrder\OrderBackOrderUpdateRequest;
use App\Bundles\Order\Responses\OrderBackOrder\OrderBackOrderDestroyResponse;
use App\Bundles\Order\Responses\OrderBackOrder\OrderBackOrderQueryResponse;
use App\Bundles\Order\Responses\OrderBackOrder\OrderBackOrderResponse;
use App\Bundles\Order\Services\OrderBackOrderBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderBackOrderController extends BaseController
{
    #[OA\Post(path: '/orderBackOrder/query', summary: '查询退货订单列表接口', security: [['bearerAuth' => []]], tags: ['退货订单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderBackOrderQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderBackOrderQueryResponse::class))]
    public function query(OrderBackOrderQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderBackOrderQueryRequest::getOrderId])) {
                $condition[] = [OrderBackOrderEntity::getOrderId, '=', $requestData[OrderBackOrderQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderBackOrderQueryRequest::getUserId])) {
                $condition[] = [OrderBackOrderEntity::getUserId, '=', $requestData[OrderBackOrderQueryRequest::getUserId]];
            }
            if (isset($requestData[OrderBackOrderQueryRequest::getBackId])) {
                $condition[] = [OrderBackOrderEntity::getBackId, '=', $requestData[OrderBackOrderQueryRequest::getBackId]];
            }

            $orderBackOrderBundleService = new OrderBackOrderBundleService;
            $result = $orderBackOrderBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderBackOrderResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new OrderBackOrderQueryResponse($result);
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

    #[OA\Post(path: '/orderBackOrder/store', summary: '新增退货订单接口', security: [['bearerAuth' => []]], tags: ['退货订单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderBackOrderCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderBackOrderResponse::class))]
    public function store(OrderBackOrderCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new OrderBackOrderEntity($requestData);

            $orderBackOrderBundleService = new OrderBackOrderBundleService;
            if ($orderBackOrderBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderBackOrder/show', summary: '获取退货订单详情接口', security: [['bearerAuth' => []]], tags: ['退货订单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderBackOrderResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $orderBackOrderBundleService = new OrderBackOrderBundleService;
            $orderBackOrder = $orderBackOrderBundleService->getOneById($id);
            if (empty($orderBackOrder)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new OrderBackOrderResponse($orderBackOrder);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderBackOrder/update', summary: '更新退货订单接口', security: [['bearerAuth' => []]], tags: ['退货订单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderBackOrderUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderBackOrderResponse::class))]
    public function update(OrderBackOrderUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderBackOrderBundleService = new OrderBackOrderBundleService;
            $orderBackOrder = $orderBackOrderBundleService->getOneById($id);
            if (empty($orderBackOrder)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new OrderBackOrderEntity($requestData);

            $orderBackOrderBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/orderBackOrder/destroy', summary: '删除退货订单接口', security: [['bearerAuth' => []]], tags: ['退货订单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderBackOrderDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderBackOrderDestroyResponse::class))]
    public function destroy(OrderBackOrderDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $orderBackOrderBundleService = new OrderBackOrderBundleService;
            if ($orderBackOrderBundleService->removeByIds($requestData['ids'])) {
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
