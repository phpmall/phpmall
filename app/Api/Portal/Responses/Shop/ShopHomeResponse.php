<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Shop;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalShopHomeResponse')]
class ShopHomeResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'shop', description: '店铺信息', ref: ShopResponse::class)]
    private array $shop;

    #[OA\Property(property: 'categories', description: '商品分类树', type: 'array', items: new OA\Items(type: 'object'))]
    private array $categories;

    #[OA\Property(property: 'products', description: '商品列表', type: 'array', items: new OA\Items(type: 'object'))]
    private array $products;

    #[OA\Property(property: 'reviews', description: '评价列表', type: 'array', items: new OA\Items(type: 'object'))]
    private array $reviews;

    public function getShop(): array
    {
        return $this->shop;
    }

    public function setShop(array $shop): void
    {
        $this->shop = $shop;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    public function getReviews(): array
    {
        return $this->reviews;
    }

    public function setReviews(array $reviews): void
    {
        $this->reviews = $reviews;
    }
}
