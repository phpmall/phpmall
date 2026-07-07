<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Order\OrderIndexRequest;
use App\Api\User\Requests\Order\OrderPreviewRequest;
use App\Api\User\Requests\Order\OrderStoreRequest;
use App\Api\User\Responses\Order\OrderListResponse;
use App\Api\User\Responses\Order\OrderPreviewResponse;
use App\Api\User\Responses\Order\OrderResponse;
use App\Modules\Order\Services\OrderService;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;

class OrderController extends BaseController
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {
        parent::__construct();
    }

    #[OA\Post(path: '/orders/preview', security: [['bearerAuth' => []]], summary: '订单预览', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPreviewRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderPreviewResponse::class))]
    public function preview(OrderPreviewRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        try {
            $response = $this->orderService->preview(
                $user->id,
                $request->input(OrderPreviewRequest::getItems, []),
                (int) $request->input(OrderPreviewRequest::getAddressId),
                $request->input(OrderPreviewRequest::getCouponId) ? (int) $request->input(OrderPreviewRequest::getCouponId) : null
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return $this->success($response->toArray());
    }

    #[OA\Get(path: '/orders', security: [['bearerAuth' => []]], summary: '订单列表', tags: ['会员中心'])]
    #[OA\Parameter(name: 'status', in: 'query', description: '订单状态', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'keyword', in: 'query', description: '搜索关键词', schema: new OA\Schema(type: 'string', nullable: true))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderListResponse::class))]
    public function index(OrderIndexRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        $response = $this->orderService->getUserOrders(
            $user->id,
            $request->input(OrderIndexRequest::getStatus) ? (int) $request->input(OrderIndexRequest::getStatus) : null,
            (string) $request->input(OrderIndexRequest::getKeyword, ''),
            (int) $request->input(OrderIndexRequest::getPage, 1),
            (int) $request->input(OrderIndexRequest::getPerPage, 20)
        );

        return $this->success($response->toArray());
    }

    #[OA\Post(path: '/orders', security: [['bearerAuth' => []]], summary: '创建订单', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderResponse::class))]
    public function store(OrderStoreRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        try {
            $response = $this->orderService->createOrder(
                $user->id,
                $request->input(OrderStoreRequest::getItems, []),
                (int) $request->input(OrderStoreRequest::getAddressId),
                $request->input(OrderStoreRequest::getRemark),
                $request->input(OrderStoreRequest::getCouponId) ? (int) $request->input(OrderStoreRequest::getCouponId) : null
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return $this->success($response->toArray());
    }

    #[OA\Get(path: '/orders/{id}', security: [['bearerAuth' => []]], summary: '订单详情', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderResponse::class))]
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);

        try {
            $response = $this->orderService->getOrderDetail($user->id, $id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return $this->success($response->toArray());
    }

    #[OA\Post(path: '/orders/{id}/cancel', security: [['bearerAuth' => []]], summary: '取消订单', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function cancel(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);

        try {
            $this->orderService->cancelOrder($user->id, $id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return $this->success();
    }

    #[OA\Post(path: '/orders/{id}/confirm', security: [['bearerAuth' => []]], summary: '确认收货', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function confirm(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);

        try {
            $this->orderService->confirmOrder($user->id, $id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return $this->success();
    }

    private function resolveUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401, '未登录');
        }

        return $user;
    }
}
