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
        property: 'list',
        description: '地址列表',
        type: 'array',
        items: new OA\Items(ref: AddressResponse::class)
    )]
    private array $list;

    #[OA\Property(property: 'total', description: '总记录数', type: 'integer')]
    private int $total;

    public function getList(): array
    {
        return $this->list;
    }

    public function setList(array $list): void
    {
        $this->list = $list;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}
