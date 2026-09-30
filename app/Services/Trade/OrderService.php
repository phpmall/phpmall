<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Domains\Order\Models\Order;
use App\Domains\Order\Models\OrderItem;
use App\Domains\Order\Models\OrderShipment;
use App\Domains\Product\Models\Product;
use App\Domains\Product\Models\ProductSku;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Exceptions\BusinessException;

class OrderService
{
    public function __construct(
        protected readonly CartService $cartService,
    ) {}

    /**
     * 生成全局唯一订单号
     */
    public function generateOrderNo(): string
    {
        return 'O'.date('YmdHis').bin2hex(random_bytes(4));
    }

    /**
     * 下单预览计算
     *
     * @param  array<int, array{sku_id: int, quantity: int}>  $items
     * @return array{
     *     items: array<int, array<string, mixed>>,
     *     product_amount: int,
     *     freight_amount: int,
     *     discount_amount: int,
     *     pay_amount: int
     * }
     */
    public function calculatePreview(array $items): array
    {
        if (empty($items)) {
            throw new BusinessException('结算商品列表不能为空');
        }

        $calculatedItems = [];
        $productAmount = 0;
        $freightAmount = 0; // 当前默认包邮或根据模板计算
        $discountAmount = 0;

        foreach ($items as $item) {
            $skuId = (int) $item['sku_id'];
            $quantity = (int) $item['quantity'];

            if ($quantity <= 0) {
                throw new BusinessException('商品购买数量必须大于0');
            }

            /** @var ProductSku|null $sku */
            $sku = ProductSku::query()->find($skuId);
            if (! $sku || $sku->status !== 1) {
                throw new BusinessException("规格ID为 {$skuId} 的商品已下架或不存在");
            }

            /** @var Product|null $product */
            $product = Product::query()->find($sku->product_id);
            if (! $product || $product->status !== 1 || $product->audit_status !== 1) {
                throw new BusinessException("商品【{$product?->title}】已下架");
            }

            if ($quantity > $sku->stock) {
                throw new BusinessException("商品【{$product->title}】库存不足，当前仅剩 {$sku->stock} 件");
            }

            $price = (int) $sku->price;
            $subtotal = $price * $quantity;
            $productAmount += $subtotal;

            $calculatedItems[] = [
                'sku_id' => $sku->id,
                'product_id' => $product->id,
                'merchant_id' => $product->merchant_id,
                'product_title' => $product->title,
                'product_image' => $sku->image ?: $product->main_image,
                'sku_specs' => $sku->sku_specs,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        $payAmount = max(0, $productAmount + $freightAmount - $discountAmount);

        return [
            'items' => $calculatedItems,
            'product_amount' => $productAmount,
            'freight_amount' => $freightAmount,
            'discount_amount' => $discountAmount,
            'pay_amount' => $payAmount,
        ];
    }

    /**
     * 创建订单（事务操作、排他锁扣库存、生成快照）
     *
     * @param  array<int, array{sku_id: int, quantity: int}>  $items
     * @param array{
     *     remark?: string|null,
     *     source?: int|null,
     *     address_id?: int|null
     * } $extra
     */
    public function createOrder(int $userId, array $items, array $extra = []): Order
    {
        $preview = $this->calculatePreview($items);
        $orderNo = $this->generateOrderNo();
        $merchantId = $preview['items'][0]['merchant_id'] ?? 1;

        return DB::transaction(function () use ($userId, $merchantId, $preview, $orderNo, $extra) {
            // 1. 严格排他锁校验并扣减库存
            $orderedSkuIds = [];
            foreach ($preview['items'] as $item) {
                /** @var ProductSku|null $sku */
                $sku = ProductSku::query()->where('id', $item['sku_id'])->lockForUpdate()->first();
                if (! $sku || $sku->stock < $item['quantity']) {
                    throw new BusinessException("商品【{$item['product_title']}】库存不足，下单失败");
                }

                $sku->decrement('stock', $item['quantity']);
                $sku->increment('sales_count', $item['quantity']);

                // 同步增加商品 SPU 销量
                Product::query()->where('id', $item['product_id'])->increment('sales_count', $item['quantity']);

                $orderedSkuIds[] = $sku->id;
            }

            // 2. 创建主订单
            $order = Order::query()->create([
                'order_no' => $orderNo,
                'user_id' => $userId,
                'merchant_id' => $merchantId,
                'order_type' => 1,
                'status' => 10, // 待付款
                'pay_status' => 0, // 未支付
                'refund_status' => 0,
                'product_amount' => $preview['product_amount'],
                'discount_amount' => $preview['discount_amount'],
                'freight_amount' => $preview['freight_amount'],
                'pay_amount' => $preview['pay_amount'],
                'remark' => $extra['remark'] ?? null,
                'source' => $extra['source'] ?? 1,
            ]);

            // 3. 创建订单明细快照
            foreach ($preview['items'] as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'sku_id' => $item['sku_id'],
                    'merchant_id' => $item['merchant_id'],
                    'product_title' => $item['product_title'],
                    'product_image' => $item['product_image'],
                    'sku_specs' => is_array($item['sku_specs']) ? json_encode($item['sku_specs'], JSON_UNESCAPED_UNICODE) : $item['sku_specs'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total_amount' => $item['subtotal'],
                    'discount_amount' => 0,
                    'refund_amount' => 0,
                    'refund_status' => 0,
                    'is_commented' => 0,
                ]);
            }

            // 4. 清理购物车已结算商品
            $this->cartService->removeOrderedSkus($userId, $orderedSkuIds);

            return $order;
        });
    }

    /**
     * 获取用户订单分页列表
     */
    public function getUserOrders(int $userId, ?int $status = null, int $page = 1, int $pageSize = 10): LengthAwarePaginator
    {
        $query = Order::query()
            ->where('user_id', $userId)
            ->orderBy('id', 'desc');

        if ($status !== null && $status > 0) {
            $query->where('status', $status);
        }

        $paginator = $query->paginate(perPage: $pageSize, page: $page);

        // 绑定明细项目
        $orderIds = collect($paginator->items())->pluck('id')->toArray();
        $items = OrderItem::query()->whereIn('order_id', $orderIds)->get()->groupBy('order_id');

        $paginator->getCollection()->transform(function (Order $order) use ($items) {
            $data = $order->toArray();
            $data['items'] = $items->get($order->id, collect())->toArray();

            return $data;
        });

        return $paginator;
    }

    /**
     * 获取用户订单详情
     *
     * @return array<string, mixed>|null
     */
    public function getOrderDetail(int $userId, int $orderId): ?array
    {
        /** @var Order|null $order */
        $order = Order::query()->where('user_id', $userId)->where('id', $orderId)->first();
        if (! $order) {
            return null;
        }

        $items = OrderItem::query()->where('order_id', $orderId)->get();
        $shipments = OrderShipment::query()->where('order_id', $orderId)->get();

        $data = $order->toArray();
        $data['items'] = $items->toArray();
        $data['shipments'] = $shipments->toArray();

        return $data;
    }

    /**
     * 用户取消订单并回退库存
     */
    public function cancelOrder(int $userId, int $orderId, string $reason = '用户自行取消'): bool
    {
        /** @var Order|null $order */
        $order = Order::query()->where('user_id', $userId)->where('id', $orderId)->first();
        if (! $order) {
            throw new BusinessException('订单不存在');
        }

        if ($order->status !== 10) {
            throw new BusinessException('只有待付款订单可直接取消');
        }

        return DB::transaction(function () use ($order, $reason) {
            // 回退库存
            $items = OrderItem::query()->where('order_id', $order->id)->get();
            foreach ($items as $item) {
                ProductSku::query()->where('id', $item->sku_id)->increment('stock', $item->quantity);
                ProductSku::query()->where('id', $item->sku_id)->decrement('sales_count', $item->quantity);
                Product::query()->where('id', $item->product_id)->decrement('sales_count', $item->quantity);
            }

            $order->status = 80; // 已取消
            $order->cancel_time = Carbon::now();
            $order->cancel_reason = $reason;

            return $order->save();
        });
    }

    /**
     * 用户确认收货
     */
    public function confirmReceipt(int $userId, int $orderId): bool
    {
        /** @var Order|null $order */
        $order = Order::query()->where('user_id', $userId)->where('id', $orderId)->first();
        if (! $order) {
            throw new BusinessException('订单不存在');
        }

        if ($order->status !== 50) {
            throw new BusinessException('当前订单状态不支持确认收货');
        }

        $order->status = 60; // 已收货
        $order->receipt_time = Carbon::now();

        return $order->save();
    }
}
