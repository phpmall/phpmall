<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Shop;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_reviews_for_product(): void
    {
        $productId = DB::table('products')->insertGetId([
            'merchant_id' => 1,
            'category_id' => 1,
            'title' => 'Test Product',
            'subtitle' => 'Test Subtitle',
            'description' => 'Test Description',
            'main_image' => 'https://example.com/image.jpg',
            'images' => json_encode(['https://example.com/image.jpg']),
            'status' => 1,
            'audit_status' => 1,
            'min_price' => 1000,
            'max_price' => 2000,
            'cost_price' => 500,
            'sales_count' => 0,
            'stock_type' => 2,
            'total_stock' => 100,
            'weight' => 100,
            'is_hot' => 0,
            'is_new' => 1,
            'is_recommend' => 0,
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('product_reviews')->insert([
            [
                'order_id' => 1,
                'order_item_id' => 1,
                'product_id' => $productId,
                'sku_id' => 1,
                'user_id' => 1,
                'merchant_id' => 1,
                'rating' => 5,
                'content' => 'Great product!',
                'images' => json_encode(['https://example.com/review1.jpg']),
                'is_anonymous' => 0,
                'is_append' => 0,
                'parent_id' => null,
                'merchant_reply' => 'Thank you!',
                'merchant_reply_at' => now(),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'order_item_id' => 2,
                'product_id' => $productId,
                'sku_id' => 1,
                'user_id' => 2,
                'merchant_id' => 1,
                'rating' => 4,
                'content' => 'Good but could be better.',
                'images' => json_encode([]),
                'is_anonymous' => 1,
                'is_append' => 0,
                'parent_id' => null,
                'merchant_reply' => null,
                'merchant_reply_at' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this->getJson("/api/shop/products/{$productId}/reviews");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
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
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.pagination.total', 2)
            ->assertJsonPath('data.summary.total', 2)
            ->assertJsonPath('data.summary.avg_rating', 4.5)
            ->assertJsonPath('data.summary.rating_5', 1)
            ->assertJsonPath('data.summary.rating_4', 1)
            ->assertJsonPath('data.summary.with_image', 1)
            ->assertJsonCount(2, 'data.items');
    }

    public function test_it_returns_404_for_nonexistent_product(): void
    {
        $response = $this->getJson('/api/shop/products/99999/reviews');

        $response->assertStatus(200)
            ->assertJson(['code' => 404]);
    }

    public function test_it_returns_empty_reviews_when_product_has_no_reviews(): void
    {
        $productId = DB::table('products')->insertGetId([
            'merchant_id' => 1,
            'category_id' => 1,
            'title' => 'Product Without Reviews',
            'subtitle' => null,
            'description' => null,
            'main_image' => 'https://example.com/image.jpg',
            'images' => json_encode([]),
            'status' => 1,
            'audit_status' => 1,
            'min_price' => 1000,
            'max_price' => 1000,
            'cost_price' => 500,
            'sales_count' => 0,
            'stock_type' => 1,
            'total_stock' => 100,
            'weight' => 100,
            'is_hot' => 0,
            'is_new' => 0,
            'is_recommend' => 0,
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson("/api/shop/products/{$productId}/reviews");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    'items',
                    'pagination',
                    'summary',
                ],
            ])
            ->assertJson(['code' => 0])
            ->assertJsonCount(0, 'data.items')
            ->assertJsonPath('data.summary.total', 0)
            ->assertJsonPath('data.summary.avg_rating', 0);
    }

    public function test_it_paginates_reviews(): void
    {
        $productId = DB::table('products')->insertGetId([
            'merchant_id' => 1,
            'category_id' => 1,
            'title' => 'Test Product',
            'subtitle' => null,
            'description' => null,
            'main_image' => 'https://example.com/image.jpg',
            'images' => json_encode([]),
            'status' => 1,
            'audit_status' => 1,
            'min_price' => 1000,
            'max_price' => 1000,
            'cost_price' => 500,
            'sales_count' => 0,
            'stock_type' => 1,
            'total_stock' => 100,
            'weight' => 100,
            'is_hot' => 0,
            'is_new' => 0,
            'is_recommend' => 0,
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $reviews = [];
        for ($i = 1; $i <= 25; $i++) {
            $reviews[] = [
                'order_id' => $i,
                'order_item_id' => $i,
                'product_id' => $productId,
                'sku_id' => 1,
                'user_id' => $i,
                'merchant_id' => 1,
                'rating' => 5,
                'content' => "Review {$i}",
                'images' => json_encode([]),
                'is_anonymous' => 0,
                'is_append' => 0,
                'parent_id' => null,
                'merchant_reply' => null,
                'merchant_reply_at' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('product_reviews')->insert($reviews);

        $response = $this->getJson("/api/shop/products/{$productId}/reviews?page=2&per_page=10");

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.pagination.total', 25)
            ->assertJsonPath('data.pagination.current_page', 2)
            ->assertJsonPath('data.pagination.per_page', 10)
            ->assertJsonCount(10, 'data.items');
    }
}
