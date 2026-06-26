<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Order\OrderRefuseRequest;
use App\Api\Seller\Requests\Order\OrderShipRequest;
use App\Api\Seller\Responses\Order\OrderListResponse;
use App\Api\Seller\Responses\Order\OrderResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class OrderController extends BaseController
{
    #[OA\Get(path: '/orders', summary: '获取订单列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/orders/{id}', summary: '获取订单详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '订单ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/orders/{id}/ship', summary: '订单发货', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '订单ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderShipRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function ship(OrderShipRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/orders/{id}/refuse', summary: '拒绝订单', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '订单ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderRefuseRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function refuse(OrderRefuseRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }
}
