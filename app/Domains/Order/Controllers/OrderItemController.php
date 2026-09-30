<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderItemEntity;
use App\Domains\Order\Requests\OrderItem\OrderItemCreateRequest;
use App\Domains\Order\Requests\OrderItem\OrderItemDestroyRequest;
use App\Domains\Order\Requests\OrderItem\OrderItemQueryRequest;
use App\Domains\Order\Requests\OrderItem\OrderItemUpdateRequest;
use App\Domains\Order\Responses\OrderItem\OrderItemDestroyResponse;
use App\Domains\Order\Responses\OrderItem\OrderItemQueryResponse;
use App\Domains\Order\Responses\OrderItem\OrderItemResponse;
use App\Domains\Order\Services\OrderItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderItemController extends BaseController
{
    public function __construct(
        private readonly OrderItemService $orderItemService,
    ) {}

    #[OA\Post(path: '/orderItem/search', summary: '查询订单商品明细列表接口', security: [['bearerAuth' => []]], tags: ['订单商品明细模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderItemQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderItemQueryResponse::class),
        ],
    ))]
    public function search(OrderItemQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderItemQueryRequest::getOrderId])) {
                $condition[] = [OrderItemEntity::getOrderId, '=', $requestData[OrderItemQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderItemQueryRequest::getProductId])) {
                $condition[] = [OrderItemEntity::getProductId, '=', $requestData[OrderItemQueryRequest::getProductId]];
            }
            if (isset($requestData[OrderItemQueryRequest::getId])) {
                $condition[] = [OrderItemEntity::getId, '=', $requestData[OrderItemQueryRequest::getId]];
            }

            $result = $this->orderItemService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderItemResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderItemQueryResponse::from($result);
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

    #[OA\Post(path: '/orderItem/store', summary: '新增订单商品明细接口', security: [['bearerAuth' => []]], tags: ['订单商品明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderItemCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderItemResponse::class),
        ],
    ))]
    public function store(OrderItemCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderItemEntity::from($requestData);

            if ($this->orderItemService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderItem/show', summary: '获取订单商品明细详情接口', security: [['bearerAuth' => []]], tags: ['订单商品明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderItemResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderItem = $this->orderItemService->getOneById($id);
            if (empty($orderItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderItemResponse::from($orderItem);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderItem/update', summary: '更新订单商品明细接口', security: [['bearerAuth' => []]], tags: ['订单商品明细模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderItemUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderItemResponse::class),
        ],
    ))]
    public function update(OrderItemUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderItem = $this->orderItemService->getOneById($id);
            if (empty($orderItem)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderItemEntity::from($requestData);

            $this->orderItemService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderItem/destroy', summary: '删除订单商品明细接口', security: [['bearerAuth' => []]], tags: ['订单商品明细模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderItemDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderItemDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderItemDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderItemService->removeByIds($requestData['ids'])) {
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
