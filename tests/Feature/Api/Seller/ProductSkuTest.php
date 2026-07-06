<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Seller;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Auth\Authentication;
use Tests\TestCase;

class ProductSkuTest extends TestCase
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

    private function createProduct(array $overrides = []): int
    {
        return DB::table('products')->insertGetId(array_merge([
            'merchant_id' => 1,
            'category_id' => 1,
            'title' => 'Test Product',
            'main_image' => 'https://example.com/image.jpg',
            'images' => json_encode(['https://example.com/image.jpg']),
            'status' => 1,
            'audit_status' => 1,
            'min_price' => 1000,
            'max_price' => 1000,
            'cost_price' => 500,
            'total_stock' => 100,
            'stock_type' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function createSku(int $productId, array $overrides = []): int
    {
        return DB::table('product_skus')->insertGetId(array_merge([
            'product_id' => $productId,
            'merchant_id' => 1,
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

    public function test_it_lists_skus(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct();
        $this->createSku($productId, ['sku_code' => 'SKU-A']);
        $this->createSku($productId, ['sku_code' => 'SKU-B']);

        $response = $this->getJson('/api/seller/product-skus');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(2, 'data.items')
            ->assertJsonPath('data.pagination.total', 2);
    }

    public function test_it_lists_skus_filtered_by_product_id(): void
    {
        $this->withSellerAuth();
        $productA = $this->createProduct();
        $productB = $this->createProduct();
        $this->createSku($productA, ['sku_code' => 'SKU-A']);
        $this->createSku($productB, ['sku_code' => 'SKU-B']);

        $response = $this->getJson("/api/seller/product-skus?product_id={$productA}");

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.skuCode', 'SKU-A');
    }

    public function test_it_creates_a_sku(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct(['min_price' => 99999, 'max_price' => 99999, 'total_stock' => 0]);

        $response = $this->postJson('/api/seller/product-skus', [
            'product_id' => $productId,
            'sku_code' => 'SKU-NEW',
            'price' => 1800,
            'stock' => 40,
            'attributes' => [['attribute_id' => 1, 'value' => 'Green']],
            'image' => 'https://example.com/green.jpg',
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.skuCode', 'SKU-NEW')
            ->assertJsonPath('data.price', 1800);

        $this->assertDatabaseHas('product_skus', [
            'product_id' => $productId,
            'sku_code' => 'SKU-NEW',
            'price' => 1800,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'min_price' => 1800,
            'max_price' => 1800,
            'total_stock' => 40,
        ]);
    }

    public function test_it_updates_a_sku(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);

        $response = $this->putJson("/api/seller/product-skus/{$skuId}", [
            'sku_code' => 'SKU-UPDATED',
            'price' => 2500,
            'stock' => 80,
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.skuCode', 'SKU-UPDATED')
            ->assertJsonPath('data.price', 2500);

        $this->assertDatabaseHas('product_skus', [
            'id' => $skuId,
            'sku_code' => 'SKU-UPDATED',
            'price' => 2500,
            'stock' => 80,
        ]);
    }

    public function test_it_deletes_a_sku(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct();
        $skuId = $this->createSku($productId);

        $response = $this->deleteJson("/api/seller/product-skus/{$skuId}");

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseMissing('product_skus', ['id' => $skuId]);
    }

    public function test_it_batch_updates_skus(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct();
        $skuA = $this->createSku($productId, ['sku_code' => 'SKU-A', 'price' => 1000, 'stock' => 10]);
        $skuB = $this->createSku($productId, ['sku_code' => 'SKU-B', 'price' => 2000, 'stock' => 20]);

        $response = $this->postJson('/api/seller/product-skus/batch', [
            'items' => [
                ['id' => $skuA, 'sku_code' => 'SKU-A-NEW', 'price' => 1100, 'stock' => 15],
                ['id' => $skuB, 'sku_code' => 'SKU-B-NEW', 'price' => 2200, 'stock' => 25],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('product_skus', [
            'id' => $skuA,
            'sku_code' => 'SKU-A-NEW',
            'price' => 1100,
            'stock' => 15,
        ]);

        $this->assertDatabaseHas('product_skus', [
            'id' => $skuB,
            'sku_code' => 'SKU-B-NEW',
            'price' => 2200,
            'stock' => 25,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'min_price' => 1100,
            'max_price' => 2200,
            'total_stock' => 40,
        ]);
    }
}
