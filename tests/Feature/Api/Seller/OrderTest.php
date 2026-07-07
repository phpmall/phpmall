<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Seller;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Auth\Authentication;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private function withSellerAuth(int $merchantId = 1): void
    {
        $user = User::factory()->create();
        $token = (new Authentication)->createToken([
            'sub' => $user->id,
            'merchant_id' => $merchantId,
            'type' => 'merchant_staff',
            'iat' => now()->timestamp,
            'exp' => now()->addHour()->timestamp,
        ]);

        $this->withHeader('Authorization', 'Bearer '.$token);
    }

    private function createOrder(array $overrides = []): int
    {
        return DB::table('orders')->insertGetId(array_merge([
            'order_no' => 'O'.now()->format('YmdHis').random_int(1000, 9999),
            'user_id' => 1,
            'merchant_id' => 1,
            'parent_order_id' => null,
            'order_type' => 1,
            'status' => 30,
            'pay_status' => 20,
            'refund_status' => 0,
            'product_amount' => 10000,
            'discount_amount' => 0,
            'freight_amount' => 500,
            'pay_amount' => 10500,
            'pay_time' => now(),
            'remark' => null,
            'source' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function createOrderItem(int $orderId, array $overrides = []): int
    {
        return DB::table('order_items')->insertGetId(array_merge([
            'order_id' => $orderId,
            'product_id' => 1,
            'sku_id' => 1,
            'merchant_id' => 1,
            'product_title' => 'Test Product',
            'product_image' => 'https://example.com/image.jpg',
            'sku_specs' => json_encode([['name' => 'Color', 'value' => 'Red']]),
            'price' => 5000,
            'quantity' => 2,
            'total_amount' => 10000,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    public function test_it_lists_orders_for_current_merchant(): void
    {
        $this->withSellerAuth();
        $orderA = $this->createOrder(['order_no' => 'OA1234567890']);
        $this->createOrder(['merchant_id' => 999, 'order_no' => 'OB1234567890']);

        $response = $this->getJson('/api/seller/orders');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $orderA)
            ->assertJsonPath('data.pagination.total', 1);
    }

    public function test_it_filters_orders_by_status(): void
    {
        $this->withSellerAuth();
        $orderA = $this->createOrder(['status' => 30, 'order_no' => 'OA1234567891']);
        $this->createOrder(['status' => 40, 'order_no' => 'OB1234567891']);

        $response = $this->getJson('/api/seller/orders?status=30');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $orderA);
    }

    public function test_it_searches_orders_by_order_no(): void
    {
        $this->withSellerAuth();
        $orderA = $this->createOrder(['order_no' => 'OA1234567892']);
        $this->createOrder(['order_no' => 'OB1234567892']);

        $response = $this->getJson('/api/seller/orders?keyword=OA1234567892');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $orderA);
    }

    public function test_it_searches_orders_by_product_title(): void
    {
        $this->withSellerAuth();
        $orderA = $this->createOrder(['order_no' => 'OA1234567893']);
        $this->createOrderItem($orderA, ['product_title' => 'Unique Product Name']);
        $orderB = $this->createOrder(['order_no' => 'OB1234567893']);
        $this->createOrderItem($orderB, ['product_title' => 'Another Product']);

        $response = $this->getJson('/api/seller/orders?keyword=Unique');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.id', $orderA);
    }

    public function test_it_shows_order_detail(): void
    {
        $this->withSellerAuth();
        $orderId = $this->createOrder();
        $this->createOrderItem($orderId);

        $response = $this->getJson("/api/seller/orders/{$orderId}");

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.id', $orderId)
            ->assertJsonPath('data.status', 30)
            ->assertJsonCount(1, 'data.items');
    }

    public function test_it_returns_404_for_other_merchant_order(): void
    {
        $this->withSellerAuth();
        $orderId = $this->createOrder(['merchant_id' => 999]);

        $response = $this->getJson("/api/seller/orders/{$orderId}");

        $response->assertStatus(200)
            ->assertJson(['code' => 404]);
    }

    public function test_it_ships_a_pending_shipment_order(): void
    {
        $this->withSellerAuth();
        $orderId = $this->createOrder(['status' => 30]);

        $response = $this->postJson("/api/seller/orders/{$orderId}/ship", [
            'logistics_company' => '顺丰速运',
            'tracking_no' => 'SF1234567890',
            'remark' => '尽快送达',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.message', '发货成功');

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'status' => 40,
        ]);
        $this->assertDatabaseHas('order_shipments', [
            'order_id' => $orderId,
            'logistics_company' => '顺丰速运',
            'tracking_no' => 'SF1234567890',
        ]);
    }

    public function test_it_rejects_shipment_for_non_shippable_order(): void
    {
        $this->withSellerAuth();
        $orderId = $this->createOrder(['status' => 10]);

        $response = $this->postJson("/api/seller/orders/{$orderId}/ship", [
            'logistics_company' => '顺丰速运',
            'tracking_no' => 'SF1234567890',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);

        $this->assertDatabaseMissing('order_shipments', [
            'order_id' => $orderId,
        ]);
    }

    public function test_it_adds_seller_remark(): void
    {
        $this->withSellerAuth();
        $orderId = $this->createOrder();

        $response = $this->postJson("/api/seller/orders/{$orderId}/remark", [
            'remark' => '优先处理',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.message', '备注成功');

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'seller_remark' => '优先处理',
        ]);
    }
}
