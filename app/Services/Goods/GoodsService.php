<?php

declare(strict_types=1);

namespace App\Services\Goods;

use App\Domains\Product\Models\Product;
use App\Domains\Product\Models\ProductCategory;
use App\Domains\Product\Models\ProductReview;
use App\Domains\Product\Models\ProductSku;
use App\Domains\Product\Services\ProductCategoryService;
use App\Domains\Product\Services\ProductReviewService;
use App\Domains\Product\Services\ProductService;
use App\Domains\Product\Services\ProductSkuService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class GoodsService
{
    public function __construct(
        protected readonly ProductService $productService,
        protected readonly ProductCategoryService $categoryService,
        protected readonly ProductSkuService $skuService,
        protected readonly ProductReviewService $reviewService,
    ) {}

    /**
     * 前台商品分页检索
     *
     * @param array{
     *     keyword?: string|null,
     *     category_id?: int|null,
     *     min_price?: int|null,
     *     max_price?: int|null,
     *     sort_by?: string|null,
     *     sort_order?: string|null,
     *     is_hot?: int|null,
     *     is_new?: int|null,
     *     is_recommend?: int|null
     * } $params
     */
    public function search(array $params = [], int $page = 1, int $pageSize = 20): LengthAwarePaginator
    {
        $query = Product::query()
            ->where('status', 1)
            ->where('audit_status', 1);

        // 关键词搜索
        if (! empty($params['keyword'])) {
            $keyword = trim((string) $params['keyword']);
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('subtitle', 'like', "%{$keyword}%")
                    ->orWhere('seo_keywords', 'like', "%{$keyword}%");
            });
        }

        // 分类筛选（包含子分类）
        if (! empty($params['category_id'])) {
            $catId = (int) $params['category_id'];
            $categoryIds = $this->getCategoryDescendantIds($catId);
            $categoryIds[] = $catId;
            $query->whereIn('category_id', array_unique($categoryIds));
        }

        // 价格区间筛选（分）
        if (isset($params['min_price']) && $params['min_price'] !== '') {
            $query->where('min_price', '>=', (int) $params['min_price']);
        }
        if (isset($params['max_price']) && $params['max_price'] !== '') {
            $query->where('max_price', '<=', (int) $params['max_price']);
        }

        // 营销标识
        if (! empty($params['is_hot'])) {
            $query->where('is_hot', 1);
        }
        if (! empty($params['is_new'])) {
            $query->where('is_new', 1);
        }
        if (! empty($params['is_recommend'])) {
            $query->where('is_recommend', 1);
        }

        // 排序规则
        $sortBy = $params['sort_by'] ?? 'sort_order';
        $sortOrder = strtolower((string) ($params['sort_order'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';

        match ($sortBy) {
            'price' => $query->orderBy('min_price', $sortOrder),
            'sales' => $query->orderBy('sales_count', $sortOrder),
            'created_at' => $query->orderBy('created_at', $sortOrder),
            default => $query->orderBy('sort_order', 'desc')->orderBy('id', 'desc'),
        };

        return $query->paginate(perPage: $pageSize, page: $page);
    }

    /**
     * 获取商品详情聚合数据（包含 SPU、可用 SKU 列表、分类信息）
     *
     * @return array{
     *     product: array<string, mixed>,
     *     skus: array<int, array<string, mixed>>,
     *     category: array<string, mixed>|null,
     *     reviews_count: int,
     *     avg_rating: float
     * }|null
     */
    public function getDetail(int $productId): ?array
    {
        /** @var Product|null $product */
        $product = Product::query()
            ->where('id', $productId)
            ->where('status', 1)
            ->where('audit_status', 1)
            ->first();

        if (! $product) {
            return null;
        }

        // 获取全部上架中的 SKU 列表
        $skus = ProductSku::query()
            ->where('product_id', $productId)
            ->where('status', 1)
            ->get();

        // 获取分类
        $category = ProductCategory::query()->find($product->category_id);

        // 获取评价统计
        $reviewsCount = ProductReview::query()
            ->where('product_id', $productId)
            ->where('status', 1)
            ->count();

        $avgRating = (float) (ProductReview::query()
            ->where('product_id', $productId)
            ->where('status', 1)
            ->avg('rating') ?? 5.0);

        return [
            'product' => $product->toArray(),
            'skus' => $skus->toArray(),
            'category' => $category?->toArray(),
            'reviews_count' => $reviewsCount,
            'avg_rating' => round($avgRating, 1),
        ];
    }

    /**
     * 获取分类树形结构
     *
     * @return array<int, array<string, mixed>>
     */
    public function getCategoryTree(): array
    {
        $categories = ProductCategory::query()
            ->where('is_show', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();

        return $this->buildTree($categories, 0);
    }

    /**
     * 递归构造分类树
     *
     * @param  array<int, array<string, mixed>>  $categories
     * @return array<int, array<string, mixed>>
     */
    protected function buildTree(array $categories, int $parentId = 0): array
    {
        $tree = [];
        foreach ($categories as $cat) {
            if ((int) $cat['parent_id'] === $parentId) {
                $children = $this->buildTree($categories, (int) $cat['id']);
                if (! empty($children)) {
                    $cat['children'] = $children;
                } else {
                    $cat['children'] = [];
                }
                $tree[] = $cat;
            }
        }

        return $tree;
    }

    /**
     * 获取指定分类的所有下级分类ID
     *
     * @return array<int, int>
     */
    public function getCategoryDescendantIds(int $categoryId): array
    {
        $all = ProductCategory::query()->where('is_show', 1)->get(['id', 'parent_id', 'path']);
        $result = [];

        foreach ($all as $item) {
            $pathParts = explode(',', trim((string) $item->path, ','));
            if (in_array((string) $categoryId, $pathParts, true)) {
                $result[] = (int) $item->id;
            }
        }

        return $result;
    }

    /**
     * 首页推荐/热销商品集合
     *
     * @return Collection<int, Product>
     */
    public function getFeaturedProducts(string $type = 'recommend', int $limit = 8): Collection
    {
        $query = Product::query()
            ->where('status', 1)
            ->where('audit_status', 1);

        match ($type) {
            'hot' => $query->where('is_hot', 1)->orderBy('sales_count', 'desc'),
            'new' => $query->where('is_new', 1)->orderBy('id', 'desc'),
            default => $query->where('is_recommend', 1)->orderBy('sort_order', 'desc'),
        };

        return $query->limit($limit)->get();
    }
}
