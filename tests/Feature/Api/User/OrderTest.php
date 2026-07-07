<?php

declare(strict_types=1);

namespace Tests\Feature\Api\User;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Redis::connection()->flushall();
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'phone' => '13800138000',
            'password' => Hash::make('password123'),
        ]);
    }

    private function loginAndGetToken(): string
    {
        $this->createUser();

        $response = $this->postJson('/api/user/auth/login', [
            'mobile' => '13800138000',
            'password' => 'password123',
            'captcha' => 'test',
            'uuid' => 'test-uuid',
            'device_name' => 'test-device',
        ]);

        return $response->json('data.access_token');
    }

    private function createMerchant(int $merchantId = 1, string $name = 'Test Merchant'): void
    {
        DB::table('merchants')->insert([
            'id' => $merchantId,
            'name' => $name,
            'contact_phone' => '13800138000',
            'contact_name' => 'Contact',
            'business_license_no' => 'BL123456',
            'settlement_cycle' => 7,
            'settlement_rate' => 0,
            'status' => 1,
            'audit_status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createProduct(int $merchantId = 1, array $overrides = []): int
    {
        return DB::table('products')->insertGetId(array_merge([
            'merchant_id' => $merchantId,
            'category_id' => 1,
            'title' => 'Test Product',
            'main_image' => 'https://example.com/product.jpg',
            'images' => json_encode(['https://example.com/product.jpg']),
            'status' => 1,
            'audit_status' => 1,
            'min_price' => 1000,
            'max_price' => 1000,
            'total_stock' => 100,
            'stock_type' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function createSku(int $productId, int $merchantId = 1, array $overrides = []): int
    {
        return DB::table('product_skus')->insertGetId(array_merge([
            'product_id' => $productId,
            'merchant_id' => $merchantId,
            'sku_code' => 'SKU-001',
            'sku_specs' => json_encode([['attribute_id' => 1, 'attribute_name' => 'Color', 'value' => 'Red']]),
            'price' => 1000,
            'market_price' => 1500,
            'cost_price' => 500,
            'stock' => 50,
            'stock_alarm' => 10,
            'weight' => 100,
            'image' => 'https://example.com/sku.jpg',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function createAddress(int $userId, array $overrides = []): int
    {
        return DB::table('user_addresses')->insertGetId(array_merge([
            'user_id' => $userId,
            'contact_name' => 'John Doe',
            'contact_phone' => '13800138000',
            'province' => 'Guangdong',
            'city' => 'Shenzhen',
            'district' => 'Nanshan',
            'detail' => 'Test Address',
            'zip_code' => '518000',
            'is_default' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function createCoupon(int $merchantId = 0, array $overrides = []): int
    {
        return DB::table('coupons')->insertGetId(array_merge([
            'merchant_id' => $merchantId,
            'name' => 'Test Coupon',
            'type' => 1,
            'scope' => 1,
            'threshold_amount' => 0,
            'discount_amount' => 500,
            'discount_rate' => 0,
            'max_discount_amount' => 0,
            'total_quantity' => 100,
            'remaining_quantity' => 100,
            'limit_per_user' => 1,
            'start_time' => now()->subDay(),
            'end_time' => now()->addDay(),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function createUserCoupon(int $userId, int $couponId): int
    {
        return DB::table('user_coupons')->insertGetId([
            'user_id' => $userId,
            'coupon_id' => $couponId,
            'status' => 0,
            'used_order_id' => 0,
            'claim_time' => now(),
            'expire_time' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_it_previews_order_amount(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $addressId = $this->createAddress($user->id);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders/preview', [
                'address_id' => $addressId,
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 3],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.totalAmount', 3000)
            ->assertJsonPath('data.freightAmount', 500)
            ->assertJsonPath('data.payAmount', 3500)
            ->assertJsonPath('data.itemCount', 3)
            ->assertJsonCount(1, 'data.merchantGroups')
            ->assertJsonPath('data.address.contact_name', 'John Doe');
    }

    public function test_it_previews_order_with_free_freight_threshold(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId, 1, ['price' => 5000]);
        $addressId = $this->createAddress($user->id);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders/preview', [
                'address_id' => $addressId,
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 2],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.totalAmount', 10000)
            ->assertJsonPath('data.freightAmount', 0)
            ->assertJsonPath('data.payAmount', 10000);
    }

    public function test_it_previews_order_with_coupon(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $addressId = $this->createAddress($user->id);
        $couponId = $this->createCoupon();
        $userCouponId = $this->createUserCoupon($user->id, $couponId);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders/preview', [
                'address_id' => $addressId,
                'coupon_id' => $userCouponId,
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 3],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.totalAmount', 3000)
            ->assertJsonPath('data.discountAmount', 500)
            ->assertJsonPath('data.payAmount', 3000);
    }

    public function test_it_creates_order_from_items(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $addressId = $this->createAddress($user->id);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders', [
                'address_id' => $addressId,
                'remark' => 'Please ship quickly',
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 2],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.status', 10)
            ->assertJsonPath('data.totalAmount', 2000)
            ->assertJsonPath('data.payAmount', 2500)
            ->assertJsonPath('data.remark', 'Please ship quickly')
            ->assertJsonCount(1, 'data.items');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => 10,
            'product_amount' => 2000,
            'freight_amount' => 500,
            'pay_amount' => 2500,
        ]);
    }

    public function test_it_splits_orders_by_merchant(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant(1, 'Merchant A');
        $this->createMerchant(2, 'Merchant B');

        $productA = $this->createProduct(1, ['title' => 'Product A']);
        $skuA = $this->createSku($productA, 1, ['price' => 1000]);

        $productB = $this->createProduct(2, ['title' => 'Product B', 'merchant_id' => 2]);
        $skuB = $this->createSku($productB, 2, ['price' => 2000]);

        $addressId = $this->createAddress($user->id);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders', [
                'address_id' => $addressId,
                'items' => [
                    ['sku_id' => $skuA, 'quantity' => 1],
                    ['sku_id' => $skuB, 'quantity' => 1],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseCount('orders', 2);
        $this->assertDatabaseCount('order_items', 2);
    }

    public function test_it_lists_orders_with_status_filter(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $addressId = $this->createAddress($user->id);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders', [
                'address_id' => $addressId,
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 1],
                ],
            ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/orders?status=10');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.pagination.total', 1);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/orders?status=80');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data.items')
            ->assertJsonPath('data.pagination.total', 0);
    }

    public function test_it_shows_order_detail(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $addressId = $this->createAddress($user->id);

        $storeResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders', [
                'address_id' => $addressId,
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 2],
                ],
            ]);

        $orderId = $storeResponse->json('data.id');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/orders/'.$orderId);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.id', $orderId)
            ->assertJsonPath('data.status', 10)
            ->assertJsonPath('data.totalAmount', 2000)
            ->assertJsonCount(1, 'data.items');
    }

    public function test_it_cancels_pending_payment_order(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $addressId = $this->createAddress($user->id);

        $storeResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders', [
                'address_id' => $addressId,
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 1],
                ],
            ]);

        $orderId = $storeResponse->json('data.id');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders/'.$orderId.'/cancel');

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'status' => 80,
        ]);
    }

    public function test_it_confirms_shipped_order(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $addressId = $this->createAddress($user->id);

        $storeResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders', [
                'address_id' => $addressId,
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 1],
                ],
            ]);

        $orderId = $storeResponse->json('data.id');

        DB::table('orders')->where('id', $orderId)->update(['status' => 50]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders/'.$orderId.'/confirm');

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'status' => 60,
        ]);
    }

    public function test_it_rejects_cancelling_non_pending_order(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $addressId = $this->createAddress($user->id);

        $storeResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders', [
                'address_id' => $addressId,
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 1],
                ],
            ]);

        $orderId = $storeResponse->json('data.id');
        DB::table('orders')->where('id', $orderId)->update(['status' => 50]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders/'.$orderId.'/cancel');

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_rejects_confirming_non_shipped_order(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $addressId = $this->createAddress($user->id);

        $storeResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders', [
                'address_id' => $addressId,
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 1],
                ],
            ]);

        $orderId = $storeResponse->json('data.id');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders/'.$orderId.'/confirm');

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_rejects_accessing_other_users_order(): void
    {
        $token = $this->loginAndGetToken();
        $otherUser = User::factory()->create();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $addressId = $this->createAddress($otherUser->id);

        $orderId = DB::table('orders')->insertGetId([
            'order_no' => 'O202607010001',
            'user_id' => $otherUser->id,
            'merchant_id' => 1,
            'status' => 10,
            'product_amount' => 1000,
            'pay_amount' => 1500,
            'freight_amount' => 500,
            'discount_amount' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('order_items')->insert([
            'order_id' => $orderId,
            'product_id' => $productId,
            'sku_id' => $skuId,
            'merchant_id' => 1,
            'product_title' => 'Test Product',
            'product_image' => 'https://example.com/product.jpg',
            'sku_specs' => json_encode([['value' => 'Red']]),
            'price' => 1000,
            'quantity' => 1,
            'total_amount' => 1000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/orders/'.$orderId);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_validates_required_fields_when_previewing(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders/preview', []);

        $response->assertStatus(422);
    }

    public function test_it_validates_required_fields_when_storing(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders', []);

        $response->assertStatus(422);
    }

    public function test_it_rejects_insufficient_stock_when_creating(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId, 1, ['stock' => 5]);
        $addressId = $this->createAddress($user->id);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/orders', [
                'address_id' => $addressId,
                'items' => [
                    ['sku_id' => $skuId, 'quantity' => 10],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_it_requires_authentication(): void
    {
        $this->getJson('/api/user/orders')->assertStatus(401);
        $this->postJson('/api/user/orders/preview')->assertStatus(401);
        $this->postJson('/api/user/orders')->assertStatus(401);
        $this->getJson('/api/user/orders/1')->assertStatus(401);
        $this->postJson('/api/user/orders/1/cancel')->assertStatus(401);
        $this->postJson('/api/user/orders/1/confirm')->assertStatus(401);
    }
}
