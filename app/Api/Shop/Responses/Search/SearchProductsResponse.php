<?php

declare(strict_types=1);

namespace App\Api\Shop\Responses\Search;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopSearchProductsResponse')]
class SearchProductsResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'items', description: '商品列表', type: 'array', items: new OA\Items(type: 'object'))]
    private array $items;

    #[OA\Property(property: 'pagination', description: '分页信息', type: 'object', properties: [
        new OA\Property(property: 'total', description: '总记录数', type: 'integer'),
        new OA\Property(property: 'per_page', description: '每页数量', type: 'integer'),
        new OA\Property(property: 'current_page', description: '当前页码', type: 'integer'),
        new OA\Property(property: 'last_page', description: '最后页码', type: 'integer'),
    ])]
    private array $pagination;

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getPagination(): array
    {
        return $this->pagination;
    }

    public function setPagination(array $pagination): void
    {
        $this->pagination = $pagination;
    }
}
