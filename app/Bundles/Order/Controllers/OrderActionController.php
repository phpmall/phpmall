<?php

declare(strict_types=1);

namespace App\Bundles\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Order\Entities\OrderActionEntity;
use App\Bundles\Order\Requests\OrderAction\OrderActionCreateRequest;
use App\Bundles\Order\Requests\OrderAction\OrderActionDestroyRequest;
use App\Bundles\Order\Requests\OrderAction\OrderActionQueryRequest;
use App\Bundles\Order\Requests\OrderAction\OrderActionUpdateRequest;
use App\Bundles\Order\Responses\OrderAction\OrderActionDestroyResponse;
use App\Bundles\Order\Responses\OrderAction\OrderActionQueryResponse;
use App\Bundles\Order\Responses\OrderAction\OrderActionResponse;
use App\Bundles\Order\Services\OrderActionBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderActionController extends BaseController
{
    #[OA\Post(path: '/orderAction/query', summary: '查询订单操作记录列表接口', security: [['bearerAuth' => []]], tags: ['订单操作记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderActionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderActionQueryResponse::class))]
    public function query(OrderActionQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderActionQueryRequest::getOrderId])) {
                $condition[] = [OrderActionEntity::getOrderId, '=', $requestData[OrderActionQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderActionQueryRequest::getActionId])) {
                $condition[] = [OrderActionEntity::getActionId, '=', $requestData[OrderActionQueryRequest::getActionId]];
            }

            $orderActionBundleService = new OrderActionBundleService;
            $result = $orderActionBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderActionResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new OrderActionQueryResponse($result);
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

    #[OA\Post(path: '/orderAction/store', summary: '新增订单操作记录接口', security: [['bearerAuth' => []]], tags: ['订单操作记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderActionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderActionResponse::class))]
    public function store(OrderActionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new OrderActionEntity($requestData);

            $orderActionBundleService = new OrderActionBundleService;
            if ($orderActionBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderAction/show', summary: '获取订单操作记录详情接口', security: [['bearerAuth' => []]], tags: ['订单操作记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderActionResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $orderActionBundleService = new OrderActionBundleService;
            $orderAction = $orderActionBundleService->getOneById($id);
            if (empty($orderAction)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new OrderActionResponse($orderAction);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderAction/update', summary: '更新订单操作记录接口', security: [['bearerAuth' => []]], tags: ['订单操作记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderActionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderActionResponse::class))]
    public function update(OrderActionUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderActionBundleService = new OrderActionBundleService;
            $orderAction = $orderActionBundleService->getOneById($id);
            if (empty($orderAction)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new OrderActionEntity($requestData);

            $orderActionBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/orderAction/destroy', summary: '删除订单操作记录接口', security: [['bearerAuth' => []]], tags: ['订单操作记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderActionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderActionDestroyResponse::class))]
    public function destroy(OrderActionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $orderActionBundleService = new OrderActionBundleService;
            if ($orderActionBundleService->removeByIds($requestData['ids'])) {
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
