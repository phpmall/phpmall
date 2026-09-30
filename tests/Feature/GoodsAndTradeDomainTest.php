<?php

declare(strict_types=1);

use App\Domains\Cart\Models\Cart;
use App\Domains\Product\Models\Product;
use App\Domains\Product\Models\ProductCategory;
use App\Domains\Product\Models\ProductSku;
use App\Models\User;
use App\Services\Goods\GoodsService;
use App\Services\Trade\CartService;
use App\Services\Trade\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Juling\Foundation\Exceptions\BusinessException;

uses(RefreshDatabase::class);

test('商品服务能正确提供分类树与分页检索', function () {
    /** @var GoodsService $goodsService */
    $goodsService = app(GoodsService::class);

    // 1. 创建分类
    $catParent = ProductCategory::query()->create([
        'parent_id' => 0,
        'name' => '数码电器',
        'level' => 1,
        'path' => '0,',
        'sort_order' => 1,
        'is_show' => 1,
    ]);

    $catChild = ProductCategory::query()->create([
        'parent_id' => $catParent->id,
        'name' => '智能手机',
        'level' => 2,
        'path' => "0,{$catParent->id},",
        'sort_order' => 1,
        'is_show' => 1,
    ]);

    $tree = $goodsService->getCategoryTree();
    expect($tree)->not->toBeEmpty()
        ->and($tree[0]['name'])->toBe('数码电器')
        ->and($tree[0]['children'][0]['name'])->toBe('智能手机');

    // 2. 创建商品
    $product = Product::query()->create([
        'merchant_id' => 1,
        'category_id' => $catChild->id,
        'title' => '旗舰手机 Pro',
        'main_image' => '/images/phone.jpg',
        'images' => ['/images/phone-1.jpg'],
        'status' => 1,
        'audit_status' => 1,
        'min_price' => 599900,
        'max_price' => 799900,
        'total_stock' => 100,
        'is_hot' => 1,
    ]);

    $result = $goodsService->search(['keyword' => '旗舰', 'category_id' => $catParent->id]);
    expect($result->total())->toBe(1)
        ->and($result->items()[0]->title)->toBe('旗舰手机 Pro');
});

test('购物车服务支持加购、库存超额拦截与金额汇总', function () {
    $user = User::factory()->create();
    /** @var CartService $cartService */
    $cartService = app(CartService::class);

    $product = Product::query()->create([
        'merchant_id' => 1,
        'category_id' => 1,
        'title' => '轻薄笔记本',
        'main_image' => '/images/laptop.jpg',
        'images' => [],
        'status' => 1,
        'audit_status' => 1,
        'min_price' => 499900,
        'max_price' => 499900,
        'total_stock' => 10,
    ]);

    $sku = ProductSku::query()->create([
        'product_id' => $product->id,
        'merchant_id' => 1,
        'sku_code' => 'SKU-LAPTOP-01',
        'sku_specs' => ['颜色' => '深空灰', '配置' => '16G+512G'],
        'price' => 499900,
        'stock' => 5,
        'status' => 1,
    ]);

    // 成功加购
    $cartItem = $cartService->addToCart($user->id, $sku->id, 2);
    expect($cartItem->quantity)->toBe(2);

    // 购物车汇总结算
    $cartList = $cartService->getCartList($user->id);
    expect($cartList['total_quantity'])->toBe(2)
        ->and($cartList['selected_amount'])->toBe(999800);

    // 超额加购拦截
    expect(fn () => $cartService->addToCart($user->id, $sku->id, 4))
        ->toThrow(BusinessException::class);
});

test('订单服务能原子锁扣库存创建订单、清空已购购物车并在取消时返还库存', function () {
    $user = User::factory()->create();
    /** @var OrderService $orderService */
    $orderService = app(OrderService::class);
    /** @var CartService $cartService */
    $cartService = app(CartService::class);

    $product = Product::query()->create([
        'merchant_id' => 1,
        'category_id' => 1,
        'title' => '高端降噪耳机',
        'main_image' => '/images/earphone.jpg',
        'images' => [],
        'status' => 1,
        'audit_status' => 1,
        'min_price' => 129900,
        'max_price' => 129900,
        'total_stock' => 20,
    ]);

    $sku = ProductSku::query()->create([
        'product_id' => $product->id,
        'merchant_id' => 1,
        'sku_code' => 'SKU-EAR-01',
        'sku_specs' => ['颜色' => '极夜黑'],
        'price' => 129900,
        'stock' => 10,
        'status' => 1,
    ]);

    // 先加入购物车
    $cartService->addToCart($user->id, $sku->id, 2);
    expect(Cart::query()->where('user_id', $user->id)->count())->toBe(1);

    // 下单 2 件
    $order = $orderService->createOrder($user->id, [
        ['sku_id' => $sku->id, 'quantity' => 2],
    ], ['remark' => '顺丰发货']);

    expect($order->status)->toBe(10)
        ->and($order->pay_amount)->toBe(259800);

    // 校验库存已扣减至 8
    $sku->refresh();
    expect($sku->stock)->toBe(8);

    // 校验购物车中该 SKU 已自动移除
    expect(Cart::query()->where('user_id', $user->id)->count())->toBe(0);

    // 取消订单并验证库存回退
    $orderService->cancelOrder($user->id, $order->id, '测试取消');
    $sku->refresh();
    expect($sku->stock)->toBe(10);
    $order->refresh();
    expect($order->status)->toBe(80);
});
