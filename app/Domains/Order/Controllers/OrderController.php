<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderEntity;
use App\Domains\Order\Services\OrderService;
use App\Domains\Order\Requests\Order\OrderCreateRequest;
use App\Domains\Order\Requests\Order\OrderDestroyRequest;
use App\Domains\Order\Requests\Order\OrderQueryRequest;
use App\Domains\Order\Requests\Order\OrderUpdateRequest;
use App\Domains\Order\Responses\Order\OrderDestroyResponse;
use App\Domains\Order\Responses\Order\OrderQueryResponse;
use App\Domains\Order\Responses\Order\OrderResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderController extends BaseController
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    #[OA\Post(path: '/order/search', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderQueryResponse::class),
        ],
    ))]
    public function search(OrderQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderQueryRequest::getCreatedAt])) {
                $condition[] = [OrderEntity::getCreatedAt, '=', $requestData[OrderQueryRequest::getCreatedAt]];
            }
            if (isset($requestData[OrderQueryRequest::getStatus])) {
                $condition[] = [OrderEntity::getStatus, '=', $requestData[OrderQueryRequest::getStatus]];
            }
            if (isset($requestData[OrderQueryRequest::getPayTime])) {
                $condition[] = [OrderEntity::getPayTime, '=', $requestData[OrderQueryRequest::getPayTime]];
            }
            if (isset($requestData[OrderQueryRequest::getPayStatus])) {
                $condition[] = [OrderEntity::getPayStatus, '=', $requestData[OrderQueryRequest::getPayStatus]];
            }
            if (isset($requestData[OrderQueryRequest::getStatus])) {
                $condition[] = [OrderEntity::getStatus, '=', $requestData[OrderQueryRequest::getStatus]];
            }
            if (isset($requestData[OrderQueryRequest::getId])) {
                $condition[] = [OrderEntity::getId, '=', $requestData[OrderQueryRequest::getId]];
            }
            if (isset($requestData[OrderQueryRequest::getOrderNo])) {
                $condition[] = [OrderEntity::getOrderNo, '=', $requestData[OrderQueryRequest::getOrderNo]];
            }
            
            $result = $this->orderService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderQueryResponse::from($result);
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

    #[OA\Post(path: '/order/store', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderResponse::class),
        ],
    ))]
    public function store(OrderCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderEntity::from($requestData);

            if ($this->orderService->save($input->toEntity())) {
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

    #[OA\Get(path: '/order/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $order = $this->orderService->getOneById($id);
            if (empty($order)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderResponse::from($order);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/order/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderResponse::class),
        ],
    ))]
    public function update(OrderUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $order = $this->orderService->getOneById($id);
            if (empty($order)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderEntity::from($requestData);

            $this->orderService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/order/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderService->removeByIds($requestData['ids'])) {
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
