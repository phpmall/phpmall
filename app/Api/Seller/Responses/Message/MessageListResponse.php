<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Message;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerMessageListResponse')]
class MessageListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'items', description: '消息列表', type: 'array', items: new OA\Items(ref: '#/components/schemas/SellerMessageResponse'))]
    private array $items;

    #[OA\Property(property: 'pagination', description: '分页信息', type: 'object')]
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
