<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Address;

use App\Api\User\Responses\AddressResponse;
use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AddressListResponse')]
class AddressListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(
        property: 'items',
        description: '地址列表',
        type: 'array',
        items: new OA\Items(ref: AddressResponse::class)
    )]
    private array $items;

    #[OA\Property(property: 'total_count', description: '总记录数', type: 'integer')]
    private int $totalCount;

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }
}
