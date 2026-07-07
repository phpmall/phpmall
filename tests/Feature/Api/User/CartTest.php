<?php

declare(strict_types=1);

namespace Tests\Feature\Api\User;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class CartTest extends TestCase
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

    private function createCartItem(int $userId, int $skuId, int $productId, int $merchantId = 1, array $overrides = []): int
    {
        return DB::table('carts')->insertGetId(array_merge([
            'user_id' => $userId,
            'merchant_id' => $merchantId,
            'product_id' => $productId,
            'sku_id' => $skuId,
            'quantity' => 1,
            'is_selected' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    public function test_it_lists_cart_items_grouped_by_merchant(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();

        $this->createMerchant(1, 'Merchant A');
        $this->createMerchant(2, 'Merchant B');

        $productA = $this->createProduct(1, ['title' => 'Product A']);
        $skuA = $this->createSku($productA, 1, ['price' => 1000]);

        $productB = $this->createProduct(2, ['title' => 'Product B', 'merchant_id' => 2]);
        $skuB = $this->createSku($productB, 2, ['price' => 2000]);

        $this->createCartItem($user->id, $skuA, $productA, 1, ['quantity' => 2]);
        $this->createCartItem($user->id, $skuB, $productB, 2, ['quantity' => 1]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user/cart');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(2, 'data.items')
            ->assertJsonPath('data.totalCount', 3)
            ->assertJsonPath('data.selectedCount', 3)
            ->assertJsonPath('data.totalAmount', 4000)
            ->assertJsonPath('data.invalidCount', 0);

        $this->assertSame(1, $response->json('data.items.0.merchantId'));
        $this->assertSame('Merchant A', $response->json('data.items.0.merchantName'));
        $this->assertSame(2, $response->json('data.items.1.merchantId'));
        $this->assertSame('Merchant B', $response->json('data.items.1.merchantName'));
    }

    public function test_it_adds_item_to_cart(): void
    {
        $token = $this->loginAndGetToken();
        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/cart', [
                'sku_id' => $skuId,
                'quantity' => 3,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.quantity', 3)
            ->assertJsonPath('data.price', 1000)
            ->assertJsonPath('data.totalPrice', 3000);

        $this->assertDatabaseHas('carts', [
            'user_id' => User::where('phone', '13800138000')->first()->id,
            'sku_id' => $skuId,
            'quantity' => 3,
            'is_selected' => 1,
        ]);
    }

    public function test_it_merges_quantity_when_adding_existing_sku(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);

        $this->createCartItem($user->id, $skuId, $productId, 1, ['quantity' => 2]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/cart', [
                'sku_id' => $skuId,
                'quantity' => 3,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.quantity', 5);

        $this->assertDatabaseCount('carts', 1);
    }

    public function test_it_validates_required_fields_when_storing(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/cart', []);

        $response->assertStatus(422);
    }

    public function test_it_rejects_adding_when_stock_insufficient(): void
    {
        $token = $this->loginAndGetToken();
        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId, 1, ['stock' => 5]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/cart', [
                'sku_id' => $skuId,
                'quantity' => 10,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);

        $this->assertDatabaseCount('carts', 0);
    }

    public function test_it_rejects_invalid_sku(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/cart', [
                'sku_id' => 99999,
                'quantity' => 1,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_updates_cart_item_quantity(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $cartId = $this->createCartItem($user->id, $skuId, $productId, 1, ['quantity' => 1]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/user/cart/'.$cartId, [
                'quantity' => 5,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.quantity', 5)
            ->assertJsonPath('data.totalPrice', 5000);

        $this->assertDatabaseHas('carts', [
            'id' => $cartId,
            'quantity' => 5,
        ]);
    }

    public function test_it_updates_cart_item_selected_status(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $cartId = $this->createCartItem($user->id, $skuId, $productId, 1, ['quantity' => 2]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/user/cart/'.$cartId, [
                'quantity' => 2,
                'is_selected' => 0,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.isSelected', 0);

        $this->assertDatabaseHas('carts', [
            'id' => $cartId,
            'is_selected' => 0,
        ]);
    }

    public function test_it_rejects_updating_other_users_cart_item(): void
    {
        $token = $this->loginAndGetToken();
        $otherUser = User::factory()->create();
        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $cartId = $this->createCartItem($otherUser->id, $skuId, $productId, 1, ['quantity' => 1]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/user/cart/'.$cartId, [
                'quantity' => 5,
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 50001]);
    }

    public function test_it_deletes_cart_item(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $cartId = $this->createCartItem($user->id, $skuId, $productId);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/user/cart/'.$cartId);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseMissing('carts', ['id' => $cartId]);
    }

    public function test_it_rejects_deleting_other_users_cart_item(): void
    {
        $token = $this->loginAndGetToken();
        $otherUser = User::factory()->create();
        $this->createMerchant();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);
        $cartId = $this->createCartItem($otherUser->id, $skuId, $productId);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/user/cart/'.$cartId);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('carts', ['id' => $cartId]);
    }

    public function test_it_clears_cart(): void
    {
        $token = $this->loginAndGetToken();
        $user = User::where('phone', '13800138000')->first();
        $this->createMerchant();
        $productId = $this->createProduct();
        $skuA = $this->createSku($productId, 1, ['sku_code' => 'SKU-A']);
        $skuB = $this->createSku($productId, 1, ['sku_code' => 'SKU-B']);

        $this->createCartItem($user->id, $skuA, $productId, 1, ['quantity' => 2]);
        $this->createCartItem($user->id, $skuB, $productId, 1, ['quantity' => 1]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/cart/clear');

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseCount('carts', 0);
    }

    public function test_it_batch_adds_items(): void
    {
        $token = $this->loginAndGetToken();
        $this->createMerchant(1, 'Merchant A');
        $this->createMerchant(2, 'Merchant B');

        $productA = $this->createProduct(1, ['title' => 'Product A']);
        $skuA = $this->createSku($productA, 1, ['sku_code' => 'SKU-A']);

        $productB = $this->createProduct(2, ['title' => 'Product B', 'merchant_id' => 2]);
        $skuB = $this->createSku($productB, 2, ['sku_code' => 'SKU-B']);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user/cart/batch', [
                'items' => [
                    ['sku_id' => $skuA, 'quantity' => 2],
                    ['sku_id' => $skuB, 'quantity' => 3],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(2, 'data');

        $this->assertDatabaseCount('carts', 2);
        $this->assertDatabaseHas('carts', ['sku_id' => $skuA, 'quantity' => 2]);
        $this->assertDatabaseHas('carts', ['sku_id' => $skuB, 'quantity' => 3]);
    }

    public function test_it_requires_authentication(): void
    {
        $response = $this->getJson('/api/user/cart');
        $response->assertStatus(401);

        $response = $this->postJson('/api/user/cart', [
            'sku_id' => 1,
            'quantity' => 1,
        ]);
        $response->assertStatus(401);
    }
}
