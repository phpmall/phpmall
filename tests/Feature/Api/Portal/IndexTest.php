<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Portal;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_home_page_data(): void
    {
        $categoryId = DB::table('product_categories')->insertGetId([
            'parent_id' => 0,
            'name' => 'Phones',
            'icon_url' => 'https://example.com/icon.png',
            'sort_order' => 1,
            'is_show' => 1,
            'level' => 1,
            'path' => '0,',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('products')->insert([
            'merchant_id' => 1,
            'category_id' => $categoryId,
            'title' => 'Recommended Product',
            'subtitle' => 'Great product',
            'main_image' => 'https://example.com/main.jpg',
            'images' => json_encode(['https://example.com/1.jpg']),
            'status' => 1,
            'audit_status' => 1,
            'min_price' => 1000,
            'max_price' => 2000,
            'is_hot' => 0,
            'is_new' => 1,
            'is_recommend' => 1,
            'sort_order' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('notifications')->insert([
            'type' => 1,
            'title' => 'Platform Notice',
            'content' => 'Welcome to PHPMall.',
            'priority' => 2,
            'status' => 1,
            'publish_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/portal/');

        $response->assertOk()
            ->assertJson(['code' => 0])
            ->assertJsonStructure([
                'data' => [
                    'banners',
                    'categories',
                    'recommend_products',
                    'notices',
                ],
            ])
            ->assertJsonCount(1, 'data.categories')
            ->assertJsonCount(1, 'data.recommend_products')
            ->assertJsonCount(1, 'data.notices')
            ->assertJsonPath('data.categories.0.name', 'Phones')
            ->assertJsonPath('data.recommend_products.0.name', 'Recommended Product')
            ->assertJsonPath('data.notices.0.title', 'Platform Notice');
    }
}
