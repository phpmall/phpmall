<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Portal;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ShopHomeTest extends TestCase
{
    use RefreshDatabase;

    private function createShop(array $overrides = []): int
    {
        return DB::table('shops')->insertGetId(array_merge([
            'merchant_id' => 1,
            'name' => 'Test Shop',
            'logo_url' => 'https://example.com/logo.png',
            'cover_url' => null,
            'description' => 'A test shop',
            'contact_phone' => '13800138000',
            'contact_name' => 'Owner',
            'status' => 1,
            'audit_status' => 1,
            'total_sales_amount' => 0,
            'total_order_count' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function createCategory(string $name, int $parentId = 0): int
    {
        return DB::table('product_categories')->insertGetId([
            'parent_id' => $parentId,
            'name' => $name,
            'icon_url' => null,
            'sort_order' => 1,
            'is_show' => 1,
            'level' => $parentId === 0 ? 1 : 2,
            'path' => $parentId === 0 ? '0' : "0,{$parentId}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createProduct(int $categoryId, array $overrides = []): int
    {
        return DB::table('products')->insertGetId(array_merge([
            'merchant_id' => 1,
            'category_id' => $categoryId,
            'title' => 'Test Product',
            'subtitle' => null,
            'description' => null,
            'main_image' => 'https://example.com/product.jpg',
            'images' => json_encode(['https://example.com/product.jpg']),
            'status' => 1,
            'audit_status' => 1,
            'min_price' => 1000,
            'max_price' => 2000,
            'cost_price' => 500,
            'sales_count' => 10,
            'stock_type' => 1,
            'total_stock' => 100,
            'weight' => 100,
            'is_hot' => 0,
            'is_new' => 1,
            'is_recommend' => 0,
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    private function createReview(int $productId, int $rating = 5, ?string $images = null): void
    {
        DB::table('product_reviews')->insert([
            'order_id' => 1,
            'order_item_id' => 1,
            'product_id' => $productId,
            'sku_id' => 1,
            'user_id' => 1,
            'merchant_id' => 1,
            'rating' => $rating,
            'content' => 'Great product!',
            'images' => $images ?? json_encode(['https://example.com/review.jpg']),
            'is_anonymous' => 0,
            'is_append' => 0,
            'parent_id' => null,
            'merchant_reply' => null,
            'merchant_reply_at' => null,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_it_returns_shop_home_data(): void
    {
        $shopId = $this->createShop();
        $categoryId = $this->createCategory('Phones');
        $productId = $this->createProduct($categoryId, ['title' => 'Shop Product']);
        $this->createReview($productId);

        $response = $this->getJson("/api/portal/shops/{$shopId}/home");

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonStructure([
                'data' => [
                    'shop',
                    'categories',
                    'products',
                    'reviews' => [
                        'items',
                        'pagination',
                    ],
                ],
            ])
            ->assertJsonPath('data.shop.id', $shopId)
            ->assertJsonPath('data.shop.name', 'Test Shop')
            ->assertJsonCount(1, 'data.categories')
            ->assertJsonCount(1, 'data.products')
            ->assertJsonPath('data.products.0.name', 'Shop Product')
            ->assertJsonCount(1, 'data.reviews.items')
            ->assertJsonPath('data.reviews.pagination.total', 1);
    }

    public function test_it_returns_404_for_nonexistent_shop(): void
    {
        $response = $this->getJson('/api/portal/shops/99999/home');

        $response->assertOk()
            ->assertJson(['code' => 404]);
    }

    public function test_it_returns_empty_data_for_shop_without_products_or_reviews(): void
    {
        $shopId = $this->createShop();
        $this->createCategory('Books');

        $response = $this->getJson("/api/portal/shops/{$shopId}/home");

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonCount(1, 'data.categories')
            ->assertJsonCount(0, 'data.products')
            ->assertJsonCount(0, 'data.reviews.items');
    }
}
