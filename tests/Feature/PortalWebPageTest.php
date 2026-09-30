<?php

declare(strict_types=1);

use App\Domains\Order\Models\Order;
use App\Domains\Product\Models\Product;
use App\Domains\Product\Models\ProductCategory;
use App\Domains\Product\Models\ProductSku;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('portal home page returns successful 200 response', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('优品商城');
});

test('portal goods list page returns successful 200 response with filters', function () {
    $category = ProductCategory::query()->create([
        'parent_id' => 0,
        'name' => '智能手机',
        'level' => 1,
        'path' => '0,',
        'sort_order' => 10,
        'is_show' => 1,
    ]);

    $response = $this->get(route('portal.goods.list', [
        'keyword' => '手机',
        'category_id' => $category->id,
    ]));

    $response->assertOk();
    $response->assertSee('智能手机');
});

test('portal goods detail page returns 200 for valid product and 404 for missing product', function () {
    $category = ProductCategory::query()->create([
        'parent_id' => 0,
        'name' => '数码影像',
        'level' => 1,
        'path' => '0,',
        'sort_order' => 1,
        'is_show' => 1,
    ]);

    $product = Product::query()->create([
        'category_id' => $category->id,
        'merchant_id' => 1,
        'title' => '高端单反相机 Pro',
        'subtitle' => '全画幅旗舰画质',
        'min_price' => 1999900,
        'max_price' => 2499900,
        'main_image' => 'https://example.com/camera.jpg',
        'images' => '[]',
        'status' => 1,
        'audit_status' => 1,
    ]);

    ProductSku::query()->create([
        'product_id' => $product->id,
        'merchant_id' => 1,
        'sku_code' => 'CAM-01',
        'price' => 1999900,
        'stock' => 10,
        'status' => 1,
        'sku_specs' => json_encode(['颜色' => '曜石黑', '套机' => '24-70mm镜头'], JSON_UNESCAPED_UNICODE),
    ]);

    $response = $this->get(route('portal.goods.show', $product->id));
    $response->assertOk();
    $response->assertSee('高端单反相机 Pro');
    $response->assertSee('曜石黑');

    $missingResponse = $this->get(route('portal.goods.show', 999999));
    $missingResponse->assertNotFound();
});

test('portal cart page returns 200 response', function () {
    $response = $this->get(route('portal.cart.view'));

    $response->assertOk();
    $response->assertSee('我的购物车');
});

test('portal checkout page returns 200 response with direct buy params', function () {
    $user = User::factory()->create(['name' => '李四']);

    $response = $this->actingAs($user)->get(route('portal.order.checkout', [
        'sku_id' => 101,
        'quantity' => 2,
    ]));

    $response->assertOk();
    $response->assertSee('填写并核对订单信息');
    $response->assertSee('李四');
});

test('portal cashier pay page returns 200 for valid order and 404 for non-existing order', function () {
    $user = User::factory()->create();

    $order = Order::query()->create([
        'order_no' => 'ORD2026093000001',
        'user_id' => $user->id,
        'merchant_id' => 1,
        'order_type' => 1,
        'status' => 10,
        'pay_status' => 0,
        'refund_status' => 0,
        'product_amount' => 59900,
        'discount_amount' => 0,
        'freight_amount' => 0,
        'pay_amount' => 59900,
        'source' => 1,
    ]);

    $response = $this->actingAs($user)->get(route('portal.order.pay', $order->order_no));
    $response->assertOk();
    $response->assertSee('ORD2026093000001');
    $response->assertSee('微信支付');
    $response->assertSee('支付宝支付');

    $missingResponse = $this->actingAs($user)->get(route('portal.order.pay', 'NOT_EXIST_ORDER'));
    $missingResponse->assertNotFound();
});

test('portal my orders page returns 200 response with status tab', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('portal.order.my', ['status' => 10]));

    $response->assertOk();
    $response->assertSee('我的订单');
    $response->assertSee('待付款');
});
