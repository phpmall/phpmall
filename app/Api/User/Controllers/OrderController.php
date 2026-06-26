<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Order\OrderIndexRequest;
use App\Api\User\Requests\Order\OrderStoreRequest;
use App\Api\User\Responses\Order\OrderListResponse;
use App\Api\User\Responses\Order\OrderResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class OrderController extends BaseController
{
    #[OA\Get(path: '/orders', security: [['bearerAuth' => []]], summary: 'Order Controller index', tags: ['会员中心'])]
    #[OA\Parameter(name: 'status', in: 'query', description: '订单状态', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'keyword', in: 'query', description: '搜索关键词', schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderListResponse::class))]
    public function index(OrderIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/orders', security: [['bearerAuth' => []]], summary: 'Order Controller store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderResponse::class))]
    public function store(OrderStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/orders/{id}', security: [['bearerAuth' => []]], summary: 'Order Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/orders/{id}/cancel', security: [['bearerAuth' => []]], summary: 'Order Controller cancel', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function cancel(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/orders/{id}/confirm', security: [['bearerAuth' => []]], summary: 'Order Controller confirm', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function confirm(): JsonResponse
    {
        return $this->success();
    }
}
