<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Shop;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ShopTest extends TestCase
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

    private function createCategory(string $name): int
    {
        return DB::table('product_categories')->insertGetId([
            'parent_id' => 0,
            'name' => $name,
            'icon_url' => null,
            'sort_order' => 1,
            'is_show' => 1,
            'level' => 1,
            'path' => '0',
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

    public function test_it_returns_products_for_shop(): void
    {
        $shopId = $this->createShop();
        $categoryId = $this->createCategory('Phones');
        $this->createProduct($categoryId, ['title' => 'Shop Product A']);
        $this->createProduct($categoryId, ['title' => 'Shop Product B']);

        $response = $this->getJson("/api/shop/shops/{$shopId}/products");

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonStructure([
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'name',
                            'mainImage',
                            'price',
                            'stock',
                            'soldCount',
                        ],
                    ],
                    'pagination' => [
                        'total',
                        'per_page',
                        'current_page',
                        'last_page',
                    ],
                ],
            ])
            ->assertJsonPath('data.pagination.total', 2)
            ->assertJsonCount(2, 'data.items');
    }

    public function test_it_filters_shop_products_by_category(): void
    {
        $shopId = $this->createShop();
        $phones = $this->createCategory('Phones');
        $books = $this->createCategory('Books');
        $this->createProduct($phones, ['title' => 'Phone']);
        $this->createProduct($books, ['title' => 'Book']);

        $response = $this->getJson("/api/shop/shops/{$shopId}/products?category_id={$phones}");

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.pagination.total', 1)
            ->assertJsonPath('data.items.0.name', 'Phone');
    }

    public function test_it_paginates_shop_products(): void
    {
        $shopId = $this->createShop();
        $categoryId = $this->createCategory('Phones');
        for ($i = 1; $i <= 5; $i++) {
            $this->createProduct($categoryId, ['title' => "Product {$i}"]);
        }

        $response = $this->getJson("/api/shop/shops/{$shopId}/products?page=2&per_page=2");

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.pagination.total', 5)
            ->assertJsonPath('data.pagination.current_page', 2)
            ->assertJsonCount(2, 'data.items');
    }

    public function test_it_returns_404_for_nonexistent_shop_products(): void
    {
        $response = $this->getJson('/api/shop/shops/99999/products');

        $response->assertOk()
            ->assertJson(['code' => 404]);
    }

    public function test_it_returns_reviews_for_shop(): void
    {
        $shopId = $this->createShop();
        $categoryId = $this->createCategory('Phones');
        $productId = $this->createProduct($categoryId);
        $this->createReview($productId, 5);
        $this->createReview($productId, 4, json_encode([]));

        $response = $this->getJson("/api/shop/shops/{$shopId}/reviews");

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonStructure([
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'productId',
                            'rating',
                            'content',
                            'images',
                            'createdAt',
                        ],
                    ],
                    'pagination' => [
                        'total',
                        'per_page',
                        'current_page',
                        'last_page',
                    ],
                    'summary' => [
                        'total',
                        'avg_rating',
                        'rating_5',
                        'rating_4',
                        'rating_3',
                        'rating_2',
                        'rating_1',
                        'with_image',
                    ],
                ],
            ])
            ->assertJsonPath('data.pagination.total', 2)
            ->assertJsonPath('data.summary.total', 2)
            ->assertJsonPath('data.summary.avg_rating', 4.5)
            ->assertJsonCount(2, 'data.items');
    }

    public function test_it_filters_shop_reviews_by_rating(): void
    {
        $shopId = $this->createShop();
        $categoryId = $this->createCategory('Phones');
        $productId = $this->createProduct($categoryId);
        $this->createReview($productId, 5);
        $this->createReview($productId, 3);

        $response = $this->getJson("/api/shop/shops/{$shopId}/reviews?rating=5");

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.pagination.total', 1)
            ->assertJsonPath('data.items.0.rating', 5);
    }

    public function test_it_filters_shop_reviews_by_has_image(): void
    {
        $shopId = $this->createShop();
        $categoryId = $this->createCategory('Phones');
        $productId = $this->createProduct($categoryId);
        $this->createReview($productId, 5, json_encode(['https://example.com/review.jpg']));
        $this->createReview($productId, 4, json_encode([]));

        $response = $this->getJson("/api/shop/shops/{$shopId}/reviews?has_image=1");

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.pagination.total', 1)
            ->assertJsonPath('data.items.0.rating', 5);
    }

    public function test_it_returns_404_for_nonexistent_shop_reviews(): void
    {
        $response = $this->getJson('/api/shop/shops/99999/reviews');

        $response->assertOk()
            ->assertJson(['code' => 404]);
    }
}
