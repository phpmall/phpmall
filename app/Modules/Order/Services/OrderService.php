<?php

declare(strict_types=1);

namespace App\Modules\Order\Services;

use App\Api\User\Responses\Order\OrderListResponse;
use App\Api\User\Responses\Order\OrderPreviewResponse;
use App\Api\User\Responses\Order\OrderResponse;
use App\Modules\Cart\Services\CartService;
use App\Modules\Coupon\Models\Coupon;
use App\Modules\Merchant\Models\Merchant;
use App\Modules\Order\Repositories\OrderRepository;
use App\Modules\Product\Models\Product;
use App\Modules\User\Models\Address;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Exceptions\BusinessException;
use Juling\Foundation\Services\CommonService;

class OrderService extends CommonService implements ServiceInterface
{
    private const int FREE_FREIGHT_THRESHOLD = 9900;

    private const int DEFAULT_FREIGHT_AMOUNT = 500;

    private const int STATUS_PENDING_PAYMENT = 10;

    private const int STATUS_CANCELLED = 80;

    private const int STATUS_RECEIVED = 60;

    public function __construct(
        private readonly OrderRepository $repository,
        private readonly OrderItemService $orderItemService,
        private readonly CartService $cartService,
    ) {}

    public function getRepository(): OrderRepository
    {
        return $this->repository;
    }

    /**
     * 预览订单金额
     */
    public function preview(int $userId, array $items, int $addressId, ?int $couponId): OrderPreviewResponse
    {
        $address = $this->resolveAddress($userId, $addressId);
        $resolvedItems = $this->resolveItems($items);
        $merchantGroups = $this->buildMerchantGroups($resolvedItems);
        $couponDiscount = $this->calculateCouponDiscount($couponId, $userId, $merchantGroups);

        $totalAmount = 0;
        $totalFreight = 0;
        $totalItemCount = 0;

        foreach ($merchantGroups as &$group) {
            $groupFreight = $this->calculateFreight($group['product_amount']);
            $group['freight_amount'] = $groupFreight;
            $totalAmount += $group['product_amount'];
            $totalFreight += $groupFreight;
            $totalItemCount += $group['item_count'];
        }
        unset($group);

        $payAmount = max(0, $totalAmount + $totalFreight - $couponDiscount);

        $response = new OrderPreviewResponse;
        $response->setTotalAmount($totalAmount);
        $response->setDiscountAmount($couponDiscount);
        $response->setFreightAmount($totalFreight);
        $response->setPayAmount($payAmount);
        $response->setItemCount($totalItemCount);
        $response->setMerchantGroups($merchantGroups);
        $response->setAddress($address ? $address->toArray() : null);

        return $response;
    }

    /**
     * 创建订单（按商家拆单）
     */
    public function createOrder(int $userId, array $items, int $addressId, ?string $remark, ?int $couponId): OrderResponse
    {
        $address = $this->resolveAddress($userId, $addressId);
        $resolvedItems = $this->resolveItems($items);
        $merchantGroups = $this->buildMerchantGroups($resolvedItems);
        $couponDiscount = $this->calculateCouponDiscount($couponId, $userId, $merchantGroups);

        $createdOrders = [];

        DB::transaction(function () use ($userId, $address, $merchantGroups, $remark, $couponId, $couponDiscount, &$createdOrders): void {
            $totalProductAmount = 0;
            foreach ($merchantGroups as $group) {
                $totalProductAmount += $group['product_amount'];
            }

            $remainingDiscount = $couponDiscount;
            $lastMerchantId = array_key_last($merchantGroups);

            foreach ($merchantGroups as $merchantId => $group) {
                $freight = $this->calculateFreight($group['product_amount']);
                $shareDiscount = $lastMerchantId === $merchantId
                    ? $remainingDiscount
                    : (int) round($couponDiscount * ($group['product_amount'] / max(1, $totalProductAmount)));
                $remainingDiscount -= $shareDiscount;

                $orderId = $this->createSingleOrder(
                    $userId,
                    $merchantId,
                    $address,
                    $group,
                    $freight,
                    $shareDiscount,
                    $remark
                );

                $createdOrders[] = $orderId;
            }

            if ($couponId !== null) {
                DB::table('user_coupons')
                    ->where('id', $couponId)
                    ->where('user_id', $userId)
                    ->update([
                        'status' => 1,
                        'used_order_id' => $createdOrders[0] ?? 0,
                        'used_at' => now(),
                    ]);
            }
        });

        if (empty($createdOrders)) {
            throw new BusinessException('订单创建失败');
        }

        return $this->buildOrderResponse((int) $createdOrders[0]);
    }

    /**
     * 查询用户订单列表
     */
    public function getUserOrders(int $userId, ?int $status, string $keyword, int $page, int $perPage): OrderListResponse
    {
        $query = $this->repository->builder()->where('orders.user_id', $userId);

        if ($status !== null) {
            $query->where('orders.status', $status);
        }

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword): void {
                $q->where('orders.order_no', 'like', '%'.$keyword.'%')
                    ->orWhereExists(function ($exists) use ($keyword): void {
                        $exists->selectRaw('1')
                            ->from('order_items')
                            ->whereColumn('order_items.order_id', 'orders.id')
                            ->where('order_items.product_title', 'like', '%'.$keyword.'%');
                    });
            });
        }

        $result = $query->orderByDesc('orders.id')->paginate($perPage, ['orders.*'], 'page', $page);
        $orders = $result->toArray();
        $total = (int) ($orders['total'] ?? 0);
        $lastPage = (int) ($orders['last_page'] ?? 1);

        $items = [];
        foreach ($orders['data'] ?? [] as $order) {
            $order = (array) $order;
            $items[] = $this->buildOrderResponse((int) $order['id'], $order)->toArray();
        }

        $response = new OrderListResponse;
        $response->setItems($items);
        $response->setPagination([
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $lastPage,
            'has_next' => $page < $lastPage,
            'has_prev' => $page > 1,
        ]);

        return $response;
    }

    /**
     * 查询订单详情
     */
    public function getOrderDetail(int $userId, int $orderId): OrderResponse
    {
        $order = $this->repository->findById($orderId);

        if (empty($order) || (int) $order['user_id'] !== $userId) {
            throw new BusinessException('订单不存在');
        }

        return $this->buildOrderResponse($orderId, $order);
    }

    /**
     * 取消订单
     */
    public function cancelOrder(int $userId, int $orderId): bool
    {
        $order = $this->repository->findById($orderId);

        if (empty($order) || (int) $order['user_id'] !== $userId) {
            throw new BusinessException('订单不存在');
        }

        if ((int) $order['status'] !== self::STATUS_PENDING_PAYMENT) {
            throw new BusinessException('只有待付款订单可以取消');
        }

        return $this->repository->updateById([
            'status' => self::STATUS_CANCELLED,
            'cancel_time' => now(),
            'cancel_reason' => '用户取消',
        ], $orderId) > 0;
    }

    /**
     * 确认收货
     */
    public function confirmOrder(int $userId, int $orderId): bool
    {
        $order = $this->repository->findById($orderId);

        if (empty($order) || (int) $order['user_id'] !== $userId) {
            throw new BusinessException('订单不存在');
        }

        $shippedStatuses = [40, 50];
        if (! in_array((int) $order['status'], $shippedStatuses, true)) {
            throw new BusinessException('只有已发货订单可以确认收货');
        }

        return $this->repository->updateById([
            'status' => self::STATUS_RECEIVED,
            'receipt_time' => now(),
        ], $orderId) > 0;
    }

    /**
     * 构建订单响应
     *
     * @param  array<string, mixed>|null  $orderData
     */
    private function buildOrderResponse(int $orderId, ?array $orderData = null): OrderResponse
    {
        $order = $orderData ?? $this->repository->findById($orderId);

        if (empty($order)) {
            throw new BusinessException('订单不存在');
        }

        $items = $this->orderItemService->getItemsByOrderId((int) $order['id']);

        $response = new OrderResponse;
        $response->setId((int) $order['id']);
        $response->setOrderNo($order['order_no']);
        $response->setStatus((int) $order['status']);
        $response->setTotalAmount((int) $order['product_amount']);
        $response->setPayAmount((int) $order['pay_amount']);
        $response->setDiscountAmount((int) $order['discount_amount']);
        $response->setFreightAmount((int) $order['freight_amount']);
        $response->setItemCount((int) ($order['item_count'] ?? $this->countItems($items)));
        $response->setRemark($order['remark']);
        $response->setCreatedAt($order['created_at']);
        $response->setPaidAt($order['pay_time']);
        $response->setShippedAt($order['ship_time']);
        $response->setConfirmedAt($order['receipt_time']);
        $response->setItems($items);

        return $response;
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function countItems(array $items): int
    {
        $count = 0;
        foreach ($items as $item) {
            $count += (int) ($item['quantity'] ?? 0);
        }

        return $count;
    }

    /**
     * 解析并校验收货地址
     */
    private function resolveAddress(int $userId, int $addressId): Address
    {
        $address = Address::where('id', $addressId)->where('user_id', $userId)->first();

        if ($address === null) {
            throw new BusinessException('收货地址不存在');
        }

        return $address;
    }

    /**
     * 解析并校验商品项
     *
     * @param  array<int, array{sku_id: int, quantity: int}>  $items
     * @return array<int, array<string, mixed>>
     */
    private function resolveItems(array $items): array
    {
        $resolved = [];

        foreach ($items as $item) {
            $skuId = (int) $item['sku_id'];
            $quantity = (int) $item['quantity'];

            $sku = (array) DB::table('product_skus')->where('id', $skuId)->first();
            if (empty($sku)) {
                throw new BusinessException('商品规格不存在');
            }

            if ((int) $sku['status'] !== 1) {
                throw new BusinessException('商品规格已下架');
            }

            $product = Product::where('id', (int) $sku['product_id'])->first();
            if ($product === null || (int) $product->status !== 1 || (int) $product->audit_status !== 1) {
                throw new BusinessException('商品已下架或未通过审核');
            }

            if ($quantity > (int) $sku['stock']) {
                throw new BusinessException('商品库存不足');
            }

            $merchant = Merchant::where('id', (int) $sku['merchant_id'])->first();

            $resolved[] = [
                'sku_id' => $skuId,
                'product_id' => (int) $sku['product_id'],
                'merchant_id' => (int) $sku['merchant_id'],
                'merchant_name' => $merchant?->name ?? '未知商家',
                'product_title' => $product->title,
                'product_image' => $sku['image'] ?? $product->main_image,
                'sku_specs' => $sku['sku_specs'],
                'price' => (int) $sku['price'],
                'quantity' => $quantity,
                'total_price' => (int) $sku['price'] * $quantity,
            ];
        }

        return $resolved;
    }

    /**
     * 按商家分组构建预览数据
     *
     * @param  array<int, array<string, mixed>>  $resolvedItems
     * @return array<int, array<string, mixed>>
     */
    private function buildMerchantGroups(array $resolvedItems): array
    {
        $groups = [];

        foreach ($resolvedItems as $item) {
            $merchantId = $item['merchant_id'];

            if (! isset($groups[$merchantId])) {
                $groups[$merchantId] = [
                    'merchant_id' => $merchantId,
                    'merchant_name' => $item['merchant_name'],
                    'product_amount' => 0,
                    'freight_amount' => 0,
                    'item_count' => 0,
                    'items' => [],
                ];
            }

            $groups[$merchantId]['product_amount'] += $item['total_price'];
            $groups[$merchantId]['item_count'] += $item['quantity'];
            $groups[$merchantId]['items'][] = [
                'sku_id' => $item['sku_id'],
                'product_id' => $item['product_id'],
                'product_name' => $item['product_title'],
                'sku_specs' => $item['sku_specs'],
                'sku_name' => $this->formatSkuSpecs($item['sku_specs']),
                'image' => $item['product_image'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total_price' => $item['total_price'],
            ];
        }

        return $groups;
    }

    /**
     * 计算运费
     */
    private function calculateFreight(int $productAmount): int
    {
        return $productAmount >= self::FREE_FREIGHT_THRESHOLD ? 0 : self::DEFAULT_FREIGHT_AMOUNT;
    }

    /**
     * 计算优惠券优惠金额
     *
     * @param  array<int, array<string, mixed>>  $merchantGroups
     */
    private function calculateCouponDiscount(?int $couponId, int $userId, array $merchantGroups): int
    {
        if ($couponId === null) {
            return 0;
        }

        $userCoupon = DB::table('user_coupons')
            ->where('id', $couponId)
            ->where('user_id', $userId)
            ->where('status', 0)
            ->first();

        if ($userCoupon === null) {
            throw new BusinessException('优惠券不可用');
        }

        $coupon = Coupon::where('id', (int) $userCoupon->coupon_id)->where('status', 1)->first();

        if ($coupon === null) {
            throw new BusinessException('优惠券不存在或已失效');
        }

        if ($coupon->start_time !== null && now()->lt($coupon->start_time)) {
            throw new BusinessException('优惠券未开始');
        }

        if ($coupon->end_time !== null && now()->gt($coupon->end_time)) {
            throw new BusinessException('优惠券已过期');
        }

        $totalProductAmount = 0;
        foreach ($merchantGroups as $group) {
            $totalProductAmount += $group['product_amount'];
        }

        if ($totalProductAmount < (int) $coupon->threshold_amount) {
            throw new BusinessException('未达到优惠券使用门槛');
        }

        if ((int) $coupon->type === 1) {
            $discount = (int) $coupon->discount_amount;
        } else {
            $discount = (int) ($totalProductAmount * ((float) $coupon->discount_rate / 100));
            if ($coupon->max_discount_amount !== null && $discount > (int) $coupon->max_discount_amount) {
                $discount = (int) $coupon->max_discount_amount;
            }
        }

        return min($discount, $totalProductAmount);
    }

    /**
     * 创建单个商家订单
     *
     * @param  array<string, mixed>  $group
     */
    private function createSingleOrder(
        int $userId,
        int $merchantId,
        Address $address,
        array $group,
        int $freight,
        int $discount,
        ?string $remark,
    ): int {
        $productAmount = $group['product_amount'];
        $payAmount = max(0, $productAmount + $freight - $discount);
        $orderNo = $this->generateOrderNo();

        $orderId = $this->repository->save([
            'order_no' => $orderNo,
            'user_id' => $userId,
            'merchant_id' => $merchantId,
            'parent_order_id' => 0,
            'order_type' => 1,
            'status' => self::STATUS_PENDING_PAYMENT,
            'pay_status' => 0,
            'refund_status' => 0,
            'product_amount' => $productAmount,
            'discount_amount' => $discount,
            'freight_amount' => $freight,
            'pay_amount' => $payAmount,
            'remark' => $remark,
            'source' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($group['items'] as $item) {
            $this->orderItemService->createItem($orderId, $merchantId, $item);
        }

        return $orderId;
    }

    /**
     * 生成订单编号
     */
    private function generateOrderNo(): string
    {
        return 'O'.date('YmdHis').str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * 格式化 SKU 规格
     */
    private function formatSkuSpecs(mixed $specs): ?string
    {
        if (empty($specs)) {
            return null;
        }

        if (is_string($specs)) {
            $specs = json_decode($specs, true);
        }

        if (! is_array($specs)) {
            return null;
        }

        $parts = [];
        foreach ($specs as $spec) {
            if (is_array($spec) && isset($spec['value'])) {
                $parts[] = (string) $spec['value'];
            }
        }

        return empty($parts) ? null : implode(' / ', $parts);
    }
}
