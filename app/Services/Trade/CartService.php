<?php

declare(strict_types=1);

namespace App\Services\Trade;

use App\Domains\Cart\Models\Cart;
use App\Domains\Product\Models\Product;
use App\Domains\Product\Models\ProductSku;
use Juling\Foundation\Exceptions\BusinessException;

class CartService
{
    /**
     * 获取指定用户的购物车列表及金额汇总结算
     *
     * @return array{
     *     items: array<int, array<string, mixed>>,
     *     total_quantity: int,
     *     selected_quantity: int,
     *     total_amount: int,
     *     selected_amount: int
     * }
     */
    public function getCartList(int $userId): array
    {
        $carts = Cart::query()
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get();

        $items = [];
        $totalQuantity = 0;
        $selectedQuantity = 0;
        $totalAmount = 0;
        $selectedAmount = 0;

        foreach ($carts as $cart) {
            /** @var ProductSku|null $sku */
            $sku = ProductSku::query()->find($cart->sku_id);
            /** @var Product|null $product */
            $product = Product::query()->find($cart->product_id);

            $isValid = $product && $product->status === 1 && $product->audit_status === 1 && $sku && $sku->status === 1;
            $currentPrice = $sku ? (int) $sku->price : 0;
            $stock = $sku ? (int) $sku->stock : 0;
            $subtotal = $currentPrice * $cart->quantity;

            $item = [
                'id' => $cart->id,
                'user_id' => $cart->user_id,
                'merchant_id' => $cart->merchant_id,
                'product_id' => $cart->product_id,
                'sku_id' => $cart->sku_id,
                'quantity' => $cart->quantity,
                'is_selected' => (bool) $cart->is_selected,
                'is_valid' => $isValid,
                'product_title' => $product?->title ?? '商品已下架',
                'product_image' => $sku?->image ?: ($product?->main_image ?? ''),
                'sku_specs' => $sku?->sku_specs ?? [],
                'price' => $currentPrice,
                'stock' => $stock,
                'subtotal' => $subtotal,
            ];

            $items[] = $item;
            $totalQuantity += $cart->quantity;
            $totalAmount += $subtotal;

            if ($cart->is_selected && $isValid) {
                $selectedQuantity += $cart->quantity;
                $selectedAmount += $subtotal;
            }
        }

        return [
            'items' => $items,
            'total_quantity' => $totalQuantity,
            'selected_quantity' => $selectedQuantity,
            'total_amount' => $totalAmount,
            'selected_amount' => $selectedAmount,
        ];
    }

    /**
     * 添加商品到购物车
     */
    public function addToCart(int $userId, int $skuId, int $quantity = 1): Cart
    {
        if ($quantity <= 0) {
            throw new BusinessException('商品加购数量必须大于0');
        }

        /** @var ProductSku|null $sku */
        $sku = ProductSku::query()->find($skuId);
        if (! $sku || $sku->status !== 1) {
            throw new BusinessException('该商品规格已下架或不存在');
        }

        /** @var Product|null $product */
        $product = Product::query()->find($sku->product_id);
        if (! $product || $product->status !== 1 || $product->audit_status !== 1) {
            throw new BusinessException('商品已下架');
        }

        /** @var Cart|null $cart */
        $cart = Cart::query()->where('user_id', $userId)->where('sku_id', $skuId)->first();

        $targetQuantity = $cart ? ($cart->quantity + $quantity) : $quantity;
        if ($targetQuantity > $sku->stock) {
            throw new BusinessException("库存不足，当前仅剩 {$sku->stock} 件");
        }

        if ($cart) {
            $cart->quantity = $targetQuantity;
            $cart->is_selected = 1;
            $cart->save();
        } else {
            $cart = Cart::query()->create([
                'user_id' => $userId,
                'merchant_id' => $product->merchant_id,
                'product_id' => $product->id,
                'sku_id' => $sku->id,
                'quantity' => $quantity,
                'is_selected' => 1,
            ]);
        }

        return $cart;
    }

    /**
     * 更新购物车条目数量
     */
    public function updateQuantity(int $userId, int $cartId, int $quantity): Cart
    {
        if ($quantity <= 0) {
            throw new BusinessException('购物车商品数量必须大于0');
        }

        /** @var Cart|null $cart */
        $cart = Cart::query()->where('user_id', $userId)->where('id', $cartId)->first();
        if (! $cart) {
            throw new BusinessException('购物车条目不存在');
        }

        /** @var ProductSku|null $sku */
        $sku = ProductSku::query()->find($cart->sku_id);
        if (! $sku || $sku->status !== 1) {
            throw new BusinessException('该商品规格已失效');
        }

        if ($quantity > $sku->stock) {
            throw new BusinessException("库存不足，最多可选 {$sku->stock} 件");
        }

        $cart->quantity = $quantity;
        $cart->save();

        return $cart;
    }

    /**
     * 批量切换购物车选中状态
     *
     * @param  array<int, int>  $cartIds
     */
    public function selectItems(int $userId, array $cartIds, bool $isSelected): int
    {
        return Cart::query()
            ->where('user_id', $userId)
            ->whereIn('id', $cartIds)
            ->update(['is_selected' => $isSelected ? 1 : 0]);
    }

    /**
     * 全选或全不选
     */
    public function selectAll(int $userId, bool $isSelected): int
    {
        return Cart::query()
            ->where('user_id', $userId)
            ->update(['is_selected' => $isSelected ? 1 : 0]);
    }

    /**
     * 删除购物车条目
     *
     * @param  array<int, int>  $cartIds
     */
    public function removeItems(int $userId, array $cartIds): int
    {
        return Cart::query()
            ->where('user_id', $userId)
            ->whereIn('id', $cartIds)
            ->delete();
    }

    /**
     * 清理指定已选购的商品条目
     *
     * @param  array<int, int>  $skuIds
     */
    public function removeOrderedSkus(int $userId, array $skuIds): int
    {
        return Cart::query()
            ->where('user_id', $userId)
            ->whereIn('sku_id', $skuIds)
            ->delete();
    }
}
