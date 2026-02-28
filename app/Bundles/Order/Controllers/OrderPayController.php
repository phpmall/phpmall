<?php

declare(strict_types=1);

namespace App\Bundles\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Order\Entities\OrderPayEntity;
use App\Bundles\Order\Requests\OrderPay\OrderPayCreateRequest;
use App\Bundles\Order\Requests\OrderPay\OrderPayDestroyRequest;
use App\Bundles\Order\Requests\OrderPay\OrderPayQueryRequest;
use App\Bundles\Order\Requests\OrderPay\OrderPayUpdateRequest;
use App\Bundles\Order\Responses\OrderPay\OrderPayDestroyResponse;
use App\Bundles\Order\Responses\OrderPay\OrderPayQueryResponse;
use App\Bundles\Order\Responses\OrderPay\OrderPayResponse;
use App\Bundles\Order\Services\OrderPayBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderPayController extends BaseController
{
    #[OA\Post(path: '/orderPay/query', summary: '查询订单支付记录列表接口', security: [['bearerAuth' => []]], tags: ['订单支付记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPayQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderPayQueryResponse::class))]
    public function query(OrderPayQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderPayQueryRequest::getLogId])) {
                $condition[] = [OrderPayEntity::getLogId, '=', $requestData[OrderPayQueryRequest::getLogId]];
            }

            $orderPayBundleService = new OrderPayBundleService;
            $result = $orderPayBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderPayResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new OrderPayQueryResponse($result);
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

    #[OA\Post(path: '/orderPay/store', summary: '新增订单支付记录接口', security: [['bearerAuth' => []]], tags: ['订单支付记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPayCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderPayResponse::class))]
    public function store(OrderPayCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new OrderPayEntity($requestData);

            $orderPayBundleService = new OrderPayBundleService;
            if ($orderPayBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderPay/show', summary: '获取订单支付记录详情接口', security: [['bearerAuth' => []]], tags: ['订单支付记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderPayResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $orderPayBundleService = new OrderPayBundleService;
            $orderPay = $orderPayBundleService->getOneById($id);
            if (empty($orderPay)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new OrderPayResponse($orderPay);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderPay/update', summary: '更新订单支付记录接口', security: [['bearerAuth' => []]], tags: ['订单支付记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPayUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderPayResponse::class))]
    public function update(OrderPayUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderPayBundleService = new OrderPayBundleService;
            $orderPay = $orderPayBundleService->getOneById($id);
            if (empty($orderPay)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new OrderPayEntity($requestData);

            $orderPayBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/orderPay/destroy', summary: '删除订单支付记录接口', security: [['bearerAuth' => []]], tags: ['订单支付记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPayDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderPayDestroyResponse::class))]
    public function destroy(OrderPayDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $orderPayBundleService = new OrderPayBundleService;
            if ($orderPayBundleService->removeByIds($requestData['ids'])) {
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
