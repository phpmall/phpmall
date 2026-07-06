<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Shop;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_searches_products_by_keyword(): void
    {
        $categoryId = $this->createCategory('Phones');
        $this->createProduct([
            'title' => 'iPhone 15',
            'category_id' => $categoryId,
            'min_price' => 599900,
            'max_price' => 699900,
        ]);
        $this->createProduct([
            'title' => 'Android Phone',
            'category_id' => $categoryId,
            'min_price' => 299900,
            'max_price' => 399900,
        ]);
        $this->createProduct([
            'title' => 'Laptop',
            'category_id' => $categoryId,
            'min_price' => 499900,
        ]);

        $response = $this->getJson('/api/shop/search/products?keyword=phone');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonStructure([
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'name',
                            'mainImage',
                            'price',
                            'marketPrice',
                            'categoryId',
                            'categoryName',
                            'createdAt',
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

    public function test_it_filters_products_by_category_and_price(): void
    {
        $phones = $this->createCategory('Phones');
        $books = $this->createCategory('Books');

        $this->createProduct([
            'title' => 'iPhone 15',
            'category_id' => $phones,
            'min_price' => 599900,
        ]);
        $this->createProduct([
            'title' => 'Android Phone',
            'category_id' => $phones,
            'min_price' => 299900,
        ]);
        $this->createProduct([
            'title' => 'Novel',
            'category_id' => $books,
            'min_price' => 5000,
        ]);

        $response = $this->getJson("/api/shop/search/products?keyword=phone&category_id={$phones}&min_price=300000&max_price=700000");

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.pagination.total', 1)
            ->assertJsonPath('data.items.0.name', 'iPhone 15');
    }

    public function test_it_paginates_search_results(): void
    {
        $categoryId = $this->createCategory('Phones');

        for ($i = 1; $i <= 5; $i++) {
            $this->createProduct([
                'title' => "Phone {$i}",
                'category_id' => $categoryId,
                'min_price' => $i * 10000,
                'sort_order' => $i,
            ]);
        }

        $response = $this->getJson('/api/shop/search/products?keyword=phone&page=2&per_page=2');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonPath('data.pagination.total', 5)
            ->assertJsonPath('data.pagination.current_page', 2)
            ->assertJsonPath('data.pagination.per_page', 2)
            ->assertJsonCount(2, 'data.items');
    }

    public function test_it_returns_search_suggestions(): void
    {
        $categoryId = $this->createCategory('Phones');
        $this->createProduct([
            'title' => 'iPhone 15 Pro',
            'category_id' => $categoryId,
        ]);
        $this->createProduct([
            'title' => 'iPhone 15',
            'category_id' => $categoryId,
        ]);
        $this->createProduct([
            'title' => 'Android Phone',
            'category_id' => $categoryId,
        ]);

        $response = $this->getJson('/api/shop/search/suggest?keyword=iphone&limit=5');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonStructure(['data' => ['suggestions']])
            ->assertJsonCount(2, 'data.suggestions');
    }

    public function test_it_returns_hot_keywords(): void
    {
        $categoryId = $this->createCategory('Phones');
        $this->createProduct([
            'title' => 'Hot Phone',
            'category_id' => $categoryId,
            'sales_count' => 100,
        ]);
        $this->createProduct([
            'title' => 'Cold Phone',
            'category_id' => $categoryId,
            'sales_count' => 1,
        ]);

        $response = $this->getJson('/api/shop/search/hot-keywords?limit=5');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonStructure([
                'data' => [
                    'keywords' => [
                        '*' => [
                            'keyword',
                            'hot_value',
                        ],
                    ],
                ],
            ])
            ->assertJsonPath('data.keywords.0.keyword', 'Hot Phone')
            ->assertJsonPath('data.keywords.0.hot_value', 100);
    }

    public function test_it_returns_search_filters(): void
    {
        $categoryId = $this->createCategory('Phones');
        $this->createProduct([
            'title' => 'iPhone 15',
            'category_id' => $categoryId,
            'min_price' => 599900,
        ]);

        $response = $this->getJson('/api/shop/search/filters?keyword=iphone');

        $response->assertStatus(200)
            ->assertJson(['code' => 0])
            ->assertJsonStructure([
                'data' => [
                    'categories',
                    'priceRanges',
                    'brands',
                    'attributes',
                ],
            ])
            ->assertJsonPath('data.categories.0.name', 'Phones')
            ->assertJsonPath('data.categories.0.count', 1)
            ->assertJsonPath('data.priceRanges', []);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createProduct(array $overrides = []): int
    {
        return DB::table('products')->insertGetId(array_merge([
            'merchant_id' => 1,
            'category_id' => 1,
            'title' => 'Test Product',
            'subtitle' => null,
            'description' => null,
            'main_image' => 'https://example.com/image.jpg',
            'images' => json_encode(['https://example.com/image.jpg']),
            'status' => 1,
            'audit_status' => 1,
            'min_price' => 1000,
            'max_price' => 2000,
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
        ], $overrides));
    }

    private function createCategory(string $name): int
    {
        return DB::table('product_categories')->insertGetId([
            'parent_id' => 0,
            'name' => $name,
            'icon_url' => null,
            'sort_order' => 0,
            'is_show' => 1,
            'level' => 1,
            'path' => '0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
