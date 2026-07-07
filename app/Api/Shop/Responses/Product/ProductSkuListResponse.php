<?php

declare(strict_types=1);

namespace App\Api\Shop\Responses\Product;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopProductSkuListResponse')]
class ProductSkuListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'product', description: '商品信息', ref: ProductResponse::class)]
    private ProductResponse $product;

    #[OA\Property(property: 'skus', description: 'SKU列表', type: 'array', items: new OA\Items(ref: ProductSkuResponse::class))]
    private array $skus;

    public function getProduct(): ProductResponse
    {
        return $this->product;
    }

    public function setProduct(ProductResponse $product): void
    {
        $this->product = $product;
    }

    public function getSkus(): array
    {
        return $this->skus;
    }

    public function setSkus(array $skus): void
    {
        $this->skus = $skus;
    }
}
