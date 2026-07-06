<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Seller;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Juling\Auth\Authentication;
use Tests\TestCase;

class ProductTest extends TestCase
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
            'subtitle' => 'Test Subtitle',
            'description' => 'Test Description',
            'main_image' => 'https://example.com/image.jpg',
            'images' => json_encode(['https://example.com/image.jpg']),
            'status' => 0,
            'audit_status' => 1,
            'min_price' => 1000,
            'max_price' => 2000,
            'cost_price' => 500,
            'total_stock' => 100,
            'stock_type' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    public function test_it_lists_products(): void
    {
        $this->withSellerAuth();
        $this->createProduct(['title' => 'Product A', 'status' => 1]);
        $this->createProduct(['title' => 'Product B', 'status' => 0]);

        $response = $this->getJson('/api/seller/products');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(2, 'data.items')
            ->assertJsonPath('data.pagination.total', 2);
    }

    public function test_it_filters_products_by_status(): void
    {
        $this->withSellerAuth();
        $this->createProduct(['title' => 'On Shelf', 'status' => 1]);
        $this->createProduct(['title' => 'Off Shelf', 'status' => 0]);

        $response = $this->getJson('/api/seller/products?status=1');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.name', 'On Shelf');
    }

    public function test_it_creates_a_product(): void
    {
        $this->withSellerAuth();

        $response = $this->postJson('/api/seller/products', [
            'name' => 'New Product',
            'description' => 'A new product',
            'category_id' => 2,
            'price' => 1500,
            'market_price' => 2000,
            'cost_price' => 800,
            'stock' => 50,
            'images' => ['https://example.com/a.jpg', 'https://example.com/b.jpg'],
            'attributes' => [['id' => 1, 'name' => 'Color', 'values' => ['Red', 'Blue']]],
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.name', 'New Product')
            ->assertJsonPath('data.price', 1500)
            ->assertJsonPath('data.stock', 50);

        $this->assertDatabaseHas('products', [
            'title' => 'New Product',
            'merchant_id' => 1,
            'min_price' => 1500,
            'max_price' => 2000,
        ]);
    }

    public function test_it_creates_a_product_with_skus(): void
    {
        $this->withSellerAuth();

        $response = $this->postJson('/api/seller/products', [
            'name' => 'SKU Product',
            'category_id' => 1,
            'price' => 1000,
            'stock' => 0,
            'skus' => [
                [
                    'sku_code' => 'SKU-RED',
                    'price' => 1000,
                    'stock' => 30,
                    'attributes' => [['attribute_id' => 1, 'value' => 'Red']],
                ],
                [
                    'sku_code' => 'SKU-BLUE',
                    'price' => 1200,
                    'stock' => 20,
                    'attributes' => [['attribute_id' => 1, 'value' => 'Blue']],
                ],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseCount('product_skus', 2);
        $this->assertDatabaseHas('products', [
            'title' => 'SKU Product',
            'min_price' => 1000,
            'max_price' => 1200,
            'total_stock' => 50,
        ]);
    }

    public function test_it_shows_a_product(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct(['title' => 'Show Me']);

        $response = $this->getJson("/api/seller/products/{$productId}");

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.name', 'Show Me')
            ->assertJsonPath('data.id', $productId);
    }

    public function test_it_returns_404_for_other_merchant_product(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct(['merchant_id' => 999, 'title' => 'Other Merchant']);

        $response = $this->getJson("/api/seller/products/{$productId}");

        $response->assertStatus(200)
            ->assertJson(['code' => 404]);
    }

    public function test_it_updates_a_product(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct();

        $response = $this->putJson("/api/seller/products/{$productId}", [
            'name' => 'Updated Product',
            'category_id' => 3,
            'price' => 2500,
            'stock' => 200,
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.name', 'Updated Product')
            ->assertJsonPath('data.price', 2500);

        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'title' => 'Updated Product',
            'min_price' => 2500,
            'total_stock' => 200,
        ]);
    }

    public function test_it_deletes_a_product(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct();

        $response = $this->deleteJson("/api/seller/products/{$productId}");

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertSoftDeleted('products', ['id' => $productId]);
    }

    public function test_it_puts_a_product_on_shelf(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct(['status' => 0]);

        $response = $this->postJson("/api/seller/products/{$productId}/on-shelf");

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('products', ['id' => $productId, 'status' => 1]);
    }

    public function test_it_takes_a_product_off_shelf(): void
    {
        $this->withSellerAuth();
        $productId = $this->createProduct(['status' => 1]);

        $response = $this->postJson("/api/seller/products/{$productId}/off-shelf");

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('products', ['id' => $productId, 'status' => 0]);
    }

    public function test_it_batch_puts_products_on_shelf(): void
    {
        $this->withSellerAuth();
        $idA = $this->createProduct(['status' => 0]);
        $idB = $this->createProduct(['status' => 0]);

        $response = $this->postJson('/api/seller/products/batch/on-shelf', [
            'ids' => [$idA, $idB],
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('products', ['id' => $idA, 'status' => 1]);
        $this->assertDatabaseHas('products', ['id' => $idB, 'status' => 1]);
    }

    public function test_it_batch_takes_products_off_shelf(): void
    {
        $this->withSellerAuth();
        $idA = $this->createProduct(['status' => 1]);
        $idB = $this->createProduct(['status' => 1]);

        $response = $this->postJson('/api/seller/products/batch/off-shelf', [
            'ids' => [$idA, $idB],
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertDatabaseHas('products', ['id' => $idA, 'status' => 0]);
        $this->assertDatabaseHas('products', ['id' => $idB, 'status' => 0]);
    }

    public function test_it_batch_deletes_products(): void
    {
        $this->withSellerAuth();
        $idA = $this->createProduct();
        $idB = $this->createProduct();

        $response = $this->postJson('/api/seller/products/batch/delete', [
            'ids' => [$idA, $idB],
        ]);

        $response->assertStatus(200)
            ->assertJson(['code' => 0]);

        $this->assertSoftDeleted('products', ['id' => $idA]);
        $this->assertSoftDeleted('products', ['id' => $idB]);
    }
}
