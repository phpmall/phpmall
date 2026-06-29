<?php

declare(strict_types=1);

namespace App\Api\Shop\Responses\Search;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopSearchFiltersResponse')]
class SearchFiltersResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'categories', description: '分类筛选', type: 'array', items: new OA\Items(type: 'object', properties: [
        new OA\Property(property: 'id', description: '分类ID', type: 'integer'),
        new OA\Property(property: 'name', description: '分类名称', type: 'string'),
        new OA\Property(property: 'count', description: '商品数量', type: 'integer'),
    ]))]
    private array $categories;

    #[OA\Property(property: 'price_ranges', description: '价格区间', type: 'array', items: new OA\Items(type: 'object', properties: [
        new OA\Property(property: 'min', description: '最低价格(分)', type: 'integer'),
        new OA\Property(property: 'max', description: '最高价格(分)', type: 'integer', nullable: true),
        new OA\Property(property: 'label', description: '显示标签', type: 'string'),
    ]))]
    private array $priceRanges;

    #[OA\Property(property: 'brands', description: '品牌筛选', type: 'array', items: new OA\Items(type: 'object', properties: [
        new OA\Property(property: 'id', description: '品牌ID', type: 'integer'),
        new OA\Property(property: 'name', description: '品牌名称', type: 'string'),
        new OA\Property(property: 'count', description: '商品数量', type: 'integer'),
    ]))]
    private array $brands;

    #[OA\Property(property: 'attributes', description: '属性筛选', type: 'array', items: new OA\Items(type: 'object', properties: [
        new OA\Property(property: 'id', description: '属性ID', type: 'integer'),
        new OA\Property(property: 'name', description: '属性名称', type: 'string'),
        new OA\Property(property: 'values', description: '可选值列表', type: 'array', items: new OA\Items(type: 'string')),
    ]))]
    private array $attributes;

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function getPriceRanges(): array
    {
        return $this->priceRanges;
    }

    public function setPriceRanges(array $priceRanges): void
    {
        $this->priceRanges = $priceRanges;
    }

    public function getBrands(): array
    {
        return $this->brands;
    }

    public function setBrands(array $brands): void
    {
        $this->brands = $brands;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }
}
