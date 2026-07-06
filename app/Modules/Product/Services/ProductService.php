<?php

declare(strict_types=1);

namespace App\Modules\Product\Services;

use App\Modules\Product\Repositories\ProductRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ProductService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ProductRepository $repository,
    ) {}

    public function getRepository(): ProductRepository
    {
        return $this->repository;
    }

    /**
     * 获取推荐商品
     */
    public function getRecommendProducts(int $limit = 10): array
    {
        $products = $this->repository->builder()
            ->where([
                'status' => 1,
                'audit_status' => 1,
                'is_recommend' => 1,
            ])
            ->orderByDesc('sort_order')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn ($product): array => (array) $product)
            ->all();

        return array_map(fn (array $product): array => $this->formatProduct($product), $products);
    }

    private function formatProduct(array $product): array
    {
        return [
            'id' => (int) $product['id'],
            'name' => $product['title'],
            'subtitle' => $product['subtitle'] ?? null,
            'mainImage' => $product['main_image'],
            'images' => $this->decodeImages($product['images']),
            'price' => (int) $product['min_price'],
            'marketPrice' => (int) $product['max_price'],
            'stock' => (int) $product['total_stock'],
            'soldCount' => (int) $product['sales_count'],
            'isHot' => (int) $product['is_hot'],
            'isRecommend' => (int) $product['is_recommend'],
            'status' => (int) $product['status'],
            'createdAt' => $product['created_at'],
            'updatedAt' => $product['updated_at'],
        ];
    }

    private function decodeImages(mixed $images): array
    {
        if (is_string($images)) {
            $decoded = json_decode($images, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($images) ? $images : [];
    }
}
