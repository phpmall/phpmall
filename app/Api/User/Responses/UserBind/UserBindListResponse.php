<?php

declare(strict_types=1);

namespace App\Api\User\Responses\UserBind;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserBindListResponse')]
class UserBindListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(
        property: 'items',
        description: '绑定账号列表',
        type: 'array',
        items: new OA\Items(ref: UserBindResponse::class)
    )]
    private array $items;

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
