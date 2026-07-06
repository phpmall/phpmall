<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductReviewRepository;
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
}
