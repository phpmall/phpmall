<?php

declare(strict_types=1);

use App\Domains\Product\Models\Product;
use App\Domains\Product\Models\ProductCategory;
use App\Domains\Product\Models\ProductSku;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Portal API: 商品分类树与商品列表检索接口能正常访问', function () {
    // 1. 创建分类
    $category = ProductCategory::query()->create([
        'parent_id' => 0,
        'name' => '智能数码',
        'level' => 1,
        'path' => '0,',
        'sort_order' => 1,
        'is_show' => 1,
    ]);

    // 2. 创建商品及 SKU
    $product = Product::query()->create([
        'merchant_id' => 1,
        'category_id' => $category->id,
        'title' => '高端旗舰机 2026',
        'main_image' => '/images/phone.jpg',
        'images' => '[]',
        'status' => 1,
        'audit_status' => 1,
        'min_price' => 399900,
        'max_price' => 599900,
        'total_stock' => 100,
        'sales_count' => 50,
        'is_hot' => 1,
    ]);

    ProductSku::query()->create([
        'product_id' => $product->id,
        'merchant_id' => 1,
        'sku_code' => 'SKU-001',
        'sku_specs' => json_encode(['版本' => '全网通'], JSON_UNESCAPED_UNICODE),
        'price' => 399900,
        'stock' => 50,
        'status' => 1,
    ]);

    // 3. 测试分类树接口
    $catResponse = $this->getJson('/api/portal/categories');
    $catResponse->assertOk()
        ->assertJsonPath('code', 0)
        ->assertJsonPath('data.0.name', '智能数码');

    // 4. 测试商品列表检索接口
    $goodsResponse = $this->getJson('/api/portal/goods?keyword=旗舰');
    $goodsResponse->assertOk()
        ->assertJsonPath('code', 0)
        ->assertJsonPath('data.total', 1)
        ->assertJsonPath('data.data.0.title', '高端旗舰机 2026');

    // 5. 测试商品详情接口
    $detailResponse = $this->getJson("/api/portal/goods/{$product->id}");
    $detailResponse->assertOk()
        ->assertJsonPath('code', 0)
        ->assertJsonPath('data.product.id', $product->id)
        ->assertJsonCount(1, 'data.skus');
});

test('Portal API: 购物车与下单全流程闭环验证', function () {
    $user = User::factory()->create();

    $product = Product::query()->create([
        'merchant_id' => 1,
        'category_id' => 1,
        'title' => '平板电脑',
        'main_image' => '/images/pad.jpg',
        'images' => '[]',
        'status' => 1,
        'audit_status' => 1,
        'min_price' => 299900,
        'max_price' => 299900,
        'total_stock' => 50,
    ]);

    $sku = ProductSku::query()->create([
        'product_id' => $product->id,
        'merchant_id' => 1,
        'sku_code' => 'SKU-PAD-01',
        'sku_specs' => json_encode(['颜色' => '银色'], JSON_UNESCAPED_UNICODE),
        'price' => 299900,
        'stock' => 20,
        'status' => 1,
    ]);

    // 1. 添加购物车
    $cartAddRes = $this->actingAs($user)->postJson('/api/portal/cart', [
        'sku_id' => $sku->id,
        'quantity' => 2,
    ]);
    $cartAddRes->assertOk()->assertJsonPath('code', 0);
    $cartId = $cartAddRes->json('data.id');

    // 2. 查询购物车列表与汇总
    $cartListRes = $this->actingAs($user)->getJson('/api/portal/cart');
    $cartListRes->assertOk()
        ->assertJsonPath('code', 0)
        ->assertJsonPath('data.total_quantity', 2)
        ->assertJsonPath('data.selected_amount', 599800);

    // 3. 修改购物车数量
    $updateRes = $this->actingAs($user)->putJson("/api/portal/cart/{$cartId}", [
        'quantity' => 3,
    ]);
    $updateRes->assertOk()->assertJsonPath('code', 0);

    // 4. 结算验价预览
    $previewRes = $this->actingAs($user)->postJson('/api/portal/order/preview', [
        'items' => [
            ['sku_id' => $sku->id, 'quantity' => 2],
        ],
    ]);
    $previewRes->assertOk()
        ->assertJsonPath('code', 0)
        ->assertJsonPath('data.pay_amount', 599800);

    // 5. 提交下单
    $orderRes = $this->actingAs($user)->postJson('/api/portal/order', [
        'items' => [
            ['sku_id' => $sku->id, 'quantity' => 2],
        ],
        'remark' => '请发顺丰速运',
    ]);
    $orderRes->assertOk()
        ->assertJsonPath('code', 0)
        ->assertJsonPath('data.status', 10)
        ->assertJsonPath('data.pay_amount', 599800);

    $orderId = $orderRes->json('data.order_id');

    // 6. 查看订单详情
    $detailRes = $this->actingAs($user)->getJson("/api/portal/order/{$orderId}");
    $detailRes->assertOk()
        ->assertJsonPath('code', 0)
        ->assertJsonPath('data.id', $orderId)
        ->assertJsonCount(1, 'data.items');

    // 7. 取消订单
    $cancelRes = $this->actingAs($user)->postJson("/api/portal/order/{$orderId}/cancel", [
        'reason' => '测试取消',
    ]);
    $cancelRes->assertOk()->assertJsonPath('code', 0);

    // 8. 验证订单状态已流转为已取消 80
    $afterCancel = $this->actingAs($user)->getJson("/api/portal/order/{$orderId}");
    $afterCancel->assertOk()->assertJsonPath('data.status', 80);
});
