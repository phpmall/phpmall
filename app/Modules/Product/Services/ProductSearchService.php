<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductCategoryRepository;
use App\Modules\Product\Repositories\ProductRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductSearchService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductRepository $repository,
        private readonly ProductCategoryRepository $categoryRepository,
    ) {}

    public function getRepository(): ProductRepository
    {
        return $this->repository;
    }

    /**
     * 搜索商品列表
     *
     * @param  array<string, mixed>  $params
     */
    public function search(array $params): array
    {
        $keyword = $params['keyword'] ?? '';
        $categoryId = $params['category_id'] ?? null;
        $minPrice = $params['min_price'] ?? null;
        $maxPrice = $params['max_price'] ?? null;
        $sortBy = $params['sort_by'] ?? 'created_at';
        $sortDirection = strtolower((string) ($params['sort_direction'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';
        $page = (int) ($params['page'] ?? 1);
        $perPage = (int) ($params['per_page'] ?? 20);

        $allowedSortFields = ['created_at', 'sales_count', 'min_price', 'sort_order'];
        if (! in_array($sortBy, $allowedSortFields, true)) {
            $sortBy = 'created_at';
        }

        $query = $this->baseSearchQuery($keyword);

        if (! empty($categoryId)) {
            $query->where('category_id', (int) $categoryId);
        }

        if ($minPrice !== null) {
            $query->where('min_price', '>=', (int) $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('min_price', '<=', (int) $maxPrice);
        }

        $result = $query->orderBy($sortBy, $sortDirection)->paginate($perPage, ['*'], 'page', $page);
        $data = $result->toArray();
        $categoryMap = $this->categoryMap(
            array_unique(array_column($data['data'], 'category_id'))
        );

        foreach ($data['data'] as $key => $product) {
            $data['data'][$key] = $this->normalizeProduct(
                collect($product)->toArray(),
                $categoryMap
            );
        }

        return $data;
    }

    /**
     * 搜索建议
     */
    public function suggest(string $keyword, int $limit): array
    {
        $titles = $this->repository->builder()
            ->where('status', 1)
            ->where('audit_status', 1)
            ->where('title', 'like', "%{$keyword}%")
            ->limit($limit)
            ->pluck('title')
            ->toArray();

        return array_values(array_unique($titles));
    }

    /**
     * 热搜关键词
     */
    public function hotKeywords(int $limit): array
    {
        $rows = $this->repository->builder()
            ->where('status', 1)
            ->where('audit_status', 1)
            ->orderBy('sales_count', 'desc')
            ->limit($limit)
            ->get(['title', 'sales_count'])
            ->map(fn ($row): array => (array) $row)
            ->toArray();

        $keywords = [];
        foreach ($rows as $row) {
            $keywords[] = [
                'keyword' => $row['title'],
                'hot_value' => (int) $row['sales_count'],
            ];
        }

        return $keywords;
    }

    /**
     * 获取搜索筛选条件
     *
     * @param  array<string, mixed>  $params
     */
    public function filters(array $params): array
    {
        $keyword = $params['keyword'] ?? '';
        $categoryId = $params['category_id'] ?? null;

        $query = $this->baseSearchQuery($keyword);

        if (! empty($categoryId)) {
            $query->where('category_id', (int) $categoryId);
        }

        return [
            'categories' => $this->buildCategoryFilters($query),
            'priceRanges' => $this->buildPriceRanges($query),
            'brands' => [],
            'attributes' => $this->buildAttributeFilters($query),
        ];
    }

    /**
     * 基础搜索查询构造器
     */
    private function baseSearchQuery(string $keyword): Builder
    {
        return $this->repository->builder()
            ->where('status', 1)
            ->where('audit_status', 1)
            ->where(function ($query) use ($keyword): void {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('subtitle', 'like', "%{$keyword}%")
                    ->orWhere('seo_keywords', 'like', "%{$keyword}%");
            });
    }

    /**
     * 分类ID与名称映射
     *
     * @param  array<int, int>  $categoryIds
     * @return array<int, string>
     */
    private function categoryMap(array $categoryIds): array
    {
        if (empty($categoryIds)) {
            return [];
        }

        return $this->categoryRepository->builder()
            ->whereIn('id', $categoryIds)
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * 将商品记录标准化为展示字段
     *
     * @param  array<string, mixed>  $product
     * @param  array<int, string>  $categoryMap
     * @return array<string, mixed>
     */
    private function normalizeProduct(array $product, array $categoryMap): array
    {
        $images = isset($product['images']) ? json_decode($product['images'], true) : [];
        if (! is_array($images)) {
            $images = [];
        }

        return [
            'id' => (int) $product['id'],
            'name' => $product['title'] ?? '',
            'subtitle' => $product['subtitle'] ?? null,
            'description' => $product['description'] ?? null,
            'main_image' => $product['main_image'] ?? '',
            'images' => $images,
            'category_id' => (int) $product['category_id'],
            'category_name' => $categoryMap[$product['category_id']] ?? null,
            'shop_id' => (int) ($product['merchant_id'] ?? 0),
            'shop_name' => null,
            'price' => (int) ($product['min_price'] ?? 0),
            'market_price' => (int) ($product['max_price'] ?? 0),
            'stock' => (int) ($product['total_stock'] ?? 0),
            'sold_count' => (int) ($product['sales_count'] ?? 0),
            'rating' => null,
            'review_count' => 0,
            'is_hot' => (int) ($product['is_hot'] ?? 0),
            'is_recommend' => (int) ($product['is_recommend'] ?? 0),
            'status' => (int) ($product['status'] ?? 0),
            'created_at' => $product['created_at'] ?? '',
            'updated_at' => $product['updated_at'] ?? '',
        ];
    }

    /**
     * 构建分类筛选
     */
    private function buildCategoryFilters(Builder $query): array
    {
        $categoryIds = $query->clone()->pluck('category_id')->toArray();
        if (empty($categoryIds)) {
            return [];
        }

        $counts = array_count_values($categoryIds);
        arsort($counts);

        $rows = $this->categoryRepository->builder()
            ->whereIn('id', array_keys($counts))
            ->get(['id', 'name'])
            ->map(fn ($row): array => (array) $row)
            ->toArray();

        $categories = [];
        foreach ($rows as $row) {
            $categories[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'count' => $counts[$row['id']] ?? 0,
            ];
        }

        return $categories;
    }

    /**
     * 构建价格区间筛选
     */
    private function buildPriceRanges(Builder $query): array
    {
        $stats = $query->clone()->selectRaw('MIN(min_price) as min_price, MAX(min_price) as max_price')->first();
        $min = (int) ($stats->min_price ?? 0);
        $max = (int) ($stats->max_price ?? 0);

        if ($max <= $min) {
            return [];
        }

        $ranges = [];
        $diff = $max - $min;
        $step = (int) max(100, ceil($diff / 4 / 100) * 100);
        $current = $min;

        while ($current < $max) {
            $next = min($current + $step, $max);
            $ranges[] = [
                'min' => $current,
                'max' => $next,
                'label' => "{$current}-{$next}",
            ];
            if ($next >= $max) {
                break;
            }
            $current = $next + 1;
        }

        return $ranges;
    }

    /**
     * 构建属性筛选
     */
    private function buildAttributeFilters(Builder $query): array
    {
        $productIds = $query->clone()->pluck('id')->toArray();
        if (empty($productIds)) {
            return [];
        }

        $specs = DB::table('product_skus')
            ->where('status', 1)
            ->whereIn('product_id', $productIds)
            ->pluck('sku_specs')
            ->toArray();

        $groups = [];
        foreach ($specs as $specJson) {
            $specArray = json_decode($specJson, true);
            if (! is_array($specArray)) {
                continue;
            }

            foreach ($specArray as $item) {
                if (! isset($item['attribute_name'], $item['value'])) {
                    continue;
                }

                $name = $item['attribute_name'];
                $id = (int) ($item['attribute_id'] ?? 0);
                $key = $name;

                if (! isset($groups[$key])) {
                    $groups[$key] = [
                        'id' => $id,
                        'name' => $name,
                        'values' => [],
                    ];
                }

                $groups[$key]['values'][] = (string) $item['value'];
            }
        }

        $attributes = [];
        foreach ($groups as $group) {
            $group['values'] = array_values(array_unique($group['values']));
            $attributes[] = $group;
        }

        return $attributes;
    }
}
