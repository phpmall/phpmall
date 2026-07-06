<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Shop;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductSkuTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_product_skus(): void
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

        DB::table('product_skus')->insert([
            [
                'product_id' => $productId,
                'merchant_id' => 1,
                'sku_code' => 'SKU-001-RED',
                'sku_specs' => json_encode([
                    ['attribute_id' => 1, 'attribute_name' => 'Color', 'value' => 'Red'],
                ]),
                'price' => 1500,
                'market_price' => 2000,
                'cost_price' => 500,
                'stock' => 50,
                'stock_alarm' => 10,
                'weight' => 100,
                'image' => 'https://example.com/sku-red.jpg',
                'sales_count' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $productId,
                'merchant_id' => 1,
                'sku_code' => 'SKU-001-BLUE',
                'sku_specs' => json_encode([
                    ['attribute_id' => 1, 'attribute_name' => 'Color', 'value' => 'Blue'],
                ]),
                'price' => 1600,
                'market_price' => 2100,
                'cost_price' => 600,
                'stock' => 30,
                'stock_alarm' => 10,
                'weight' => 100,
                'image' => 'https://example.com/sku-blue.jpg',
                'sales_count' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this->getJson("/api/shop/products/{$productId}/skus");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    'product' => [
                        'id',
                        'mainImage',
                        'images',
                        'categoryId',
                        'isHot',
                        'isRecommend',
                        'status',
                        'createdAt',
                        'updatedAt',
                    ],
                    'skus' => [
                        '*' => [
                            'id',
                            'productId',
                            'skuCode',
                            'price',
                            'stock',
                            'attributes',
                            'image',
                            'createdAt',
                            'updatedAt',
                        ],
                    ],
                ],
            ])
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.product.id', $productId)
            ->assertJsonCount(2, 'data.skus');
    }

    public function test_it_returns_404_for_nonexistent_product(): void
    {
        $response = $this->getJson('/api/shop/products/99999/skus');

        $response->assertStatus(200)
            ->assertJson(['code' => 404]);
    }

    public function test_it_returns_empty_skus_when_product_has_no_skus(): void
    {
        $productId = DB::table('products')->insertGetId([
            'merchant_id' => 1,
            'category_id' => 1,
            'title' => 'Product Without SKUs',
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

        $response = $this->getJson("/api/shop/products/{$productId}/skus");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'message',
                'data' => [
                    'product',
                    'skus',
                ],
            ])
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.product.id', $productId)
            ->assertJsonCount(0, 'data.skus');
    }
}
