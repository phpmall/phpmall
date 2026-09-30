<?php

declare(strict_types=1);

namespace App\Domains\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Order\Entities\OrderShipmentEntity;
use App\Domains\Order\Requests\OrderShipment\OrderShipmentCreateRequest;
use App\Domains\Order\Requests\OrderShipment\OrderShipmentDestroyRequest;
use App\Domains\Order\Requests\OrderShipment\OrderShipmentQueryRequest;
use App\Domains\Order\Requests\OrderShipment\OrderShipmentUpdateRequest;
use App\Domains\Order\Responses\OrderShipment\OrderShipmentDestroyResponse;
use App\Domains\Order\Responses\OrderShipment\OrderShipmentQueryResponse;
use App\Domains\Order\Responses\OrderShipment\OrderShipmentResponse;
use App\Domains\Order\Services\OrderShipmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderShipmentController extends BaseController
{
    public function __construct(
        private readonly OrderShipmentService $orderShipmentService,
    ) {}

    #[OA\Post(path: '/orderShipment/search', summary: '查询订单物流发货列表接口', security: [['bearerAuth' => []]], tags: ['订单物流发货模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderShipmentQueryResponse::class),
        ],
    ))]
    public function search(OrderShipmentQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderShipmentQueryRequest::getMerchantId])) {
                $condition[] = [OrderShipmentEntity::getMerchantId, '=', $requestData[OrderShipmentQueryRequest::getMerchantId]];
            }
            if (isset($requestData[OrderShipmentQueryRequest::getOrderId])) {
                $condition[] = [OrderShipmentEntity::getOrderId, '=', $requestData[OrderShipmentQueryRequest::getOrderId]];
            }
            if (isset($requestData[OrderShipmentQueryRequest::getId])) {
                $condition[] = [OrderShipmentEntity::getId, '=', $requestData[OrderShipmentQueryRequest::getId]];
            }

            $result = $this->orderShipmentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = OrderShipmentResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = OrderShipmentQueryResponse::from($result);
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

    #[OA\Post(path: '/orderShipment/store', summary: '新增订单物流发货接口', security: [['bearerAuth' => []]], tags: ['订单物流发货模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderShipmentResponse::class),
        ],
    ))]
    public function store(OrderShipmentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = OrderShipmentEntity::from($requestData);

            if ($this->orderShipmentService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderShipment/show', summary: '获取订单物流发货详情接口', security: [['bearerAuth' => []]], tags: ['订单物流发货模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderShipmentResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $orderShipment = $this->orderShipmentService->getOneById($id);
            if (empty($orderShipment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = OrderShipmentResponse::from($orderShipment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderShipment/update', summary: '更新订单物流发货接口', security: [['bearerAuth' => []]], tags: ['订单物流发货模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderShipmentResponse::class),
        ],
    ))]
    public function update(OrderShipmentUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderShipment = $this->orderShipmentService->getOneById($id);
            if (empty($orderShipment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = OrderShipmentEntity::from($requestData);

            $this->orderShipmentService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/orderShipment/destroy', summary: '删除订单物流发货接口', security: [['bearerAuth' => []]], tags: ['订单物流发货模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipmentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: OrderShipmentDestroyResponse::class),
        ],
    ))]
    public function destroy(OrderShipmentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->orderShipmentService->removeByIds($requestData['ids'])) {
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
