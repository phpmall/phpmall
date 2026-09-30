<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Order\OrderCancelRequest;
use App\Api\Portal\Requests\Order\OrderCreateRequest;
use App\Api\Portal\Requests\Order\OrderPreviewRequest;
use App\Api\Portal\Responses\Order\OrderCreatedResponse;
use App\Api\Portal\Responses\Order\OrderDetailResponse;
use App\Api\Portal\Responses\Order\OrderPreviewResponse;
use App\Services\Trade\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;

#[OA\Tag(name: '前台-订单交易接口', description: 'PC 前台商城与移动多端统一验价结算、下单、订单流转接口')]
class OrderController extends BaseController
{
    public function __construct(
        protected readonly OrderService $orderService,
    ) {}

    /**
     * 解析当前用户ID
     */
    protected function getUserId(Request $request): int
    {
        $userId = $request->user()?->id ?? $request->header('X-User-Id');
        if (! $userId) {
            throw new BusinessException('请先登录后再进行交易操作');
        }

        return (int) $userId;
    }

    #[OA\Post(path: '/api/portal/order/preview', summary: '结算预览与验价', security: [['bearerAuth' => []]], tags: ['前台-订单交易接口'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderPreviewRequest::class))]
    #[OA\Response(
        response: 200,
        description: '返回验价明细与总计金额',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(property: 'data', ref: OrderPreviewResponse::class),
            ]
        )
    )]
    public function preview(OrderPreviewRequest $request): JsonResponse
    {
        $items = $request->validated()['items'];
        $result = $this->orderService->calculatePreview($items);

        return $this->success($result);
    }

    #[OA\Post(path: '/api/portal/order', summary: '提交创建订单', security: [['bearerAuth' => []]], tags: ['前台-订单交易接口'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderCreateRequest::class))]
    #[OA\Response(
        response: 200,
        description: '下单成功，返回订单号及实付金额',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(property: 'data', ref: OrderCreatedResponse::class),
            ]
        )
    )]
    public function store(OrderCreateRequest $request): JsonResponse
    {
        $userId = $this->getUserId($request);
        $data = $request->validated();

        $order = $this->orderService->createOrder($userId, $data['items'], [
            'remark' => $data['remark'] ?? null,
            'source' => (int) ($data['source'] ?? 1),
            'address_id' => isset($data['address_id']) ? (int) $data['address_id'] : null,
        ]);

        return $this->success([
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'pay_amount' => $order->pay_amount,
            'status' => $order->status,
        ]);
    }

    #[OA\Get(path: '/api/portal/order', summary: '获取我的订单分页列表', security: [['bearerAuth' => []]], tags: ['前台-订单交易接口'])]
    #[OA\Parameter(name: 'status', description: '订单状态筛选：10-待付款，30-待发货，50-待收货，70-已完成，80-已取消', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Parameter(name: 'pageSize', description: '每页条数', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 10))]
    #[OA\Response(
        response: 200,
        description: '成功返回订单列表',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(property: 'data', type: 'object'),
            ]
        )
    )]
    public function index(Request $request): JsonResponse
    {
        $userId = $this->getUserId($request);
        $status = $request->filled('status') ? (int) $request->query('status') : null;
        $page = (int) $request->query('page', '1');
        $pageSize = (int) $request->query('pageSize', '10');

        $paginator = $this->orderService->getUserOrders($userId, $status, $page, $pageSize);

        return $this->success([
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'data' => $paginator->items(),
        ]);
    }

    #[OA\Get(path: '/api/portal/order/{id}', summary: '获取订单详情', security: [['bearerAuth' => []]], tags: ['前台-订单交易接口'])]
    #[OA\Parameter(name: 'id', description: '订单ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 10086))]
    #[OA\Response(
        response: 200,
        description: '返回订单主体、明细与物流包裹',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(property: 'data', ref: OrderDetailResponse::class),
            ]
        )
    )]
    public function show(Request $request, int $id): JsonResponse
    {
        $userId = $this->getUserId($request);
        $detail = $this->orderService->getOrderDetail($userId, $id);

        if (! $detail) {
            throw new BusinessException('订单不存在');
        }

        return $this->success($detail);
    }

    #[OA\Post(path: '/api/portal/order/{id}/cancel', summary: '用户自行取消订单', security: [['bearerAuth' => []]], tags: ['前台-订单交易接口'])]
    #[OA\Parameter(name: 'id', description: '订单ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 10086))]
    #[OA\RequestBody(required: false, content: new OA\JsonContent(ref: OrderCancelRequest::class))]
    #[OA\Response(
        response: 200,
        description: '取消成功并返还锁定库存',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
            ]
        )
    )]
    public function cancel(OrderCancelRequest $request, int $id): JsonResponse
    {
        $userId = $this->getUserId($request);
        $reason = (string) ($request->input('reason') ?: '用户自行取消');

        $this->orderService->cancelOrder($userId, $id, $reason);

        return $this->success();
    }

    #[OA\Post(path: '/api/portal/order/{id}/confirm-receipt', summary: '用户确认收货', security: [['bearerAuth' => []]], tags: ['前台-订单交易接口'])]
    #[OA\Parameter(name: 'id', description: '订单ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 10086))]
    #[OA\Response(
        response: 200,
        description: '确认收货成功',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
            ]
        )
    )]
    public function confirmReceipt(Request $request, int $id): JsonResponse
    {
        $userId = $this->getUserId($request);
        $this->orderService->confirmReceipt($userId, $id);

        return $this->success();
    }
}
