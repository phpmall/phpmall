<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderRefundEntity;
use App\Domains\Order\Requests\OrderRefund\OrderRefundCreateRequest;
use App\Domains\Order\Requests\OrderRefund\OrderRefundDestroyRequest;
use App\Domains\Order\Requests\OrderRefund\OrderRefundQueryRequest;
use App\Domains\Order\Requests\OrderRefund\OrderRefundUpdateRequest;
use App\Domains\Order\Responses\OrderRefund\OrderRefundDestroyResponse;
use App\Domains\Order\Responses\OrderRefund\OrderRefundQueryResponse;
use App\Domains\Order\Responses\OrderRefund\OrderRefundResponse;
use App\Domains\Order\Services\OrderRefundService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderRefundController extends BaseController
{
    public function __construct(
        private readonly OrderRefundService $orderRefundService,
    ) {}

    #[OA\Post(path: '/orderRefund/search', summary: '查询订单退款售后列表接口', security: [['bearerAuth' => []]], tags: ['订单退款售后模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundQueryResponse::class),
        ],
    ))]
    public function search(OrderRefundQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderRefundQueryRequest::getStatus])) {
                $condition[] = [OrderRefundEntity::getStatus, '=', $requestData[OrderRefundQueryRequest::getStatus]];
            }
            if (isset($requestData[OrderRefundQueryRequest::getOrderId])) {
                $condition[] = [OrderRefundEntity::getOrderId, '=', $requestData[OrderRefundQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderRefundQueryRequest::getUserId])) {
                $condition[] = [OrderRefundEntity::getUserId, '=', $requestData[OrderRefundQueryRequest::getUserId]];
            }
            if (isset($requestData[OrderRefundQueryRequest::getId])) {
                $condition[] = [OrderRefundEntity::getId, '=', $requestData[OrderRefundQueryRequest::getId]];
            }
            if (isset($requestData[OrderRefundQueryRequest::getRefundNo])) {
                $condition[] = [OrderRefundEntity::getRefundNo, '=', $requestData[OrderRefundQueryRequest::getRefundNo]];
            }

            $result = $this->orderRefundService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderRefundResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderRefundQueryResponse::from($result);
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

    #[OA\Post(path: '/orderRefund/store', summary: '新增订单退款售后接口', security: [['bearerAuth' => []]], tags: ['订单退款售后模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundResponse::class),
        ],
    ))]
    public function store(OrderRefundCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderRefundEntity::from($requestData);

            if ($this->orderRefundService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderRefund/show', summary: '获取订单退款售后详情接口', security: [['bearerAuth' => []]], tags: ['订单退款售后模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderRefund = $this->orderRefundService->getOneById($id);
            if (empty($orderRefund)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderRefundResponse::from($orderRefund);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderRefund/update', summary: '更新订单退款售后接口', security: [['bearerAuth' => []]], tags: ['订单退款售后模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundResponse::class),
        ],
    ))]
    public function update(OrderRefundUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderRefund = $this->orderRefundService->getOneById($id);
            if (empty($orderRefund)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderRefundEntity::from($requestData);

            $this->orderRefundService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderRefund/destroy', summary: '删除订单退款售后接口', security: [['bearerAuth' => []]], tags: ['订单退款售后模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefundDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderRefundDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderRefundDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderRefundService->removeByIds($requestData['ids'])) {
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
