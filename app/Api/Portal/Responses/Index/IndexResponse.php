<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Index;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalIndexResponse')]
class IndexResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'banners', description: '轮播图列表', type: 'array', items: new OA\Items(type: 'object'))]
    private array $banners;

    #[OA\Property(property: 'categories', description: '分类树', type: 'array', items: new OA\Items(type: 'object'))]
    private array $categories;

    #[OA\Property(property: 'recommend_products', description: '推荐商品', type: 'array', items: new OA\Items(type: 'object'))]
    private array $recommend_products;

    #[OA\Property(property: 'notices', description: '公告列表', type: 'array', items: new OA\Items(type: 'object'))]
    private array $notices;

    public function getBanners(): array
    {
        return $this->banners;
    }

    public function setBanners(array $banners): void
    {
        $this->banners = $banners;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function getRecommendProducts(): array
    {
        return $this->recommend_products;
    }

    public function setRecommendProducts(array $recommendProducts): void
    {
        $this->recommend_products = $recommendProducts;
    }

    public function getNotices(): array
    {
        return $this->notices;
    }

    public function setNotices(array $notices): void
    {
        $this->notices = $notices;
    }
}
