<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductReviewRepository;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductReviewService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductReviewRepository $repository,
    ) {}

    public function getRepository(): ProductReviewRepository
    {
        return $this->repository;
    }

    /**
     * 按商品ID分页查询评价列表
     */
    public function paginateByProductId(int $productId, int $page = 1, int $perPage = 20): array
    {
        return $this->repository->page(['product_id' => $productId, 'status' => 1], $page, $perPage, 'id', 'desc');
    }

    /**
     * 获取商品评价统计
     */
    public function getRatingStats(int $productId): array
    {
        $total = $this->repository->builder()->where('product_id', $productId)->where('status', 1)->count();
        $avgRating = $this->repository->builder()->where('product_id', $productId)->where('status', 1)->avg('rating') ?? 0;

        $rating5 = $this->repository->builder()->where('product_id', $productId)->where('status', 1)->where('rating', 5)->count();
        $rating4 = $this->repository->builder()->where('product_id', $productId)->where('status', 1)->where('rating', 4)->count();
        $rating3 = $this->repository->builder()->where('product_id', $productId)->where('status', 1)->where('rating', 3)->count();
        $rating2 = $this->repository->builder()->where('product_id', $productId)->where('status', 1)->where('rating', 2)->count();
        $rating1 = $this->repository->builder()->where('product_id', $productId)->where('status', 1)->where('rating', 1)->count();

        $withImage = $this->repository->builder()->where('product_id', $productId)->where('status', 1)->whereNotNull('images')->where('images', '!=', '[]')->count();

        return [
            'total' => $total,
            'avg_rating' => round((float) $avgRating, 1),
            'rating_5' => $rating5,
            'rating_4' => $rating4,
            'rating_3' => $rating3,
            'rating_2' => $rating2,
            'rating_1' => $rating1,
            'with_image' => $withImage,
        ];
    }

    /**
     * 按商家ID分页查询评价列表
     *
     * @param  array<string, mixed>  $filters
     */
    public function paginateByMerchantId(int $merchantId, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $builder = $this->repository->builder()
            ->where('merchant_id', $merchantId)
            ->where('status', 1);

        if (! empty($filters['rating'])) {
            $builder->where('rating', (int) $filters['rating']);
        }

        if (! empty($filters['has_image'])) {
            $builder->whereNotNull('images')->where('images', '!=', '[]');
        }

        $result = $builder->orderByDesc('id')->paginate($perPage, ['*'], 'page', $page);

        $data = $result->toArray();
        foreach ($data['data'] ?? [] as $key => $item) {
            $data['data'][$key] = collect($item)->toArray();
        }

        return $data;
    }

    /**
     * 获取商家评价统计
     */
    public function getRatingStatsByMerchantId(int $merchantId): array
    {
        $baseQuery = $this->repository->builder()->where('merchant_id', $merchantId)->where('status', 1);

        $total = (int) (clone $baseQuery)->count();
        $avgRating = (clone $baseQuery)->avg('rating') ?? 0;

        return [
            'total' => $total,
            'avg_rating' => round((float) $avgRating, 1),
            'rating_5' => (int) (clone $baseQuery)->where('rating', 5)->count(),
            'rating_4' => (int) (clone $baseQuery)->where('rating', 4)->count(),
            'rating_3' => (int) (clone $baseQuery)->where('rating', 3)->count(),
            'rating_2' => (int) (clone $baseQuery)->where('rating', 2)->count(),
            'rating_1' => (int) (clone $baseQuery)->where('rating', 1)->count(),
            'with_image' => (int) (clone $baseQuery)->whereNotNull('images')->where('images', '!=', '[]')->count(),
        ];
    }

    /**
     * 按店铺ID分页查询评价列表
     *
     * @param  array<string, mixed>  $filters
     */
    public function paginateByShopId(int $shopId, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $shop = DB::table('shops')->where('id', $shopId)->first();
        if (empty($shop)) {
            return [
                'data' => [],
                'total' => 0,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => 1,
            ];
        }

        return $this->paginateByMerchantId((int) $shop->merchant_id, $filters, $page, $perPage);
    }

    /**
     * 获取店铺评价统计
     */
    public function getRatingStatsByShopId(int $shopId): array
    {
        $shop = DB::table('shops')->where('id', $shopId)->first();
        if (empty($shop)) {
            return [
                'total' => 0,
                'avg_rating' => 0,
                'rating_5' => 0,
                'rating_4' => 0,
                'rating_3' => 0,
                'rating_2' => 0,
                'rating_1' => 0,
                'with_image' => 0,
            ];
        }

        return $this->getRatingStatsByMerchantId((int) $shop->merchant_id);
    }
}
