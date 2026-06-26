<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Data;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerDataProductsResponse')]
class DataProductsResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'total_products', description: '商品总数', type: 'integer')]
    private int $totalProducts;

    #[OA\Property(property: 'on_shelf_products', description: '上架商品数', type: 'integer')]
    private int $onShelfProducts;

    #[OA\Property(property: 'off_shelf_products', description: '下架商品数', type: 'integer')]
    private int $offShelfProducts;

    #[OA\Property(property: 'low_stock_products', description: '库存不足商品数', type: 'integer')]
    private int $lowStockProducts;

    #[OA\Property(property: 'top_selling', description: '热销商品排行', type: 'array', items: new OA\Items(type: 'object'))]
    private array $topSelling;

    public function getTotalProducts(): int
    {
        return $this->totalProducts;
    }

    public function setTotalProducts(int $totalProducts): void
    {
        $this->totalProducts = $totalProducts;
    }

    public function getOnShelfProducts(): int
    {
        return $this->onShelfProducts;
    }

    public function setOnShelfProducts(int $onShelfProducts): void
    {
        $this->onShelfProducts = $onShelfProducts;
    }

    public function getOffShelfProducts(): int
    {
        return $this->offShelfProducts;
    }

    public function setOffShelfProducts(int $offShelfProducts): void
    {
        $this->offShelfProducts = $offShelfProducts;
    }

    public function getLowStockProducts(): int
    {
        return $this->lowStockProducts;
    }

    public function setLowStockProducts(int $lowStockProducts): void
    {
        $this->lowStockProducts = $lowStockProducts;
    }

    public function getTopSelling(): array
    {
        return $this->topSelling;
    }

    public function setTopSelling(array $topSelling): void
    {
        $this->topSelling = $topSelling;
    }
}
