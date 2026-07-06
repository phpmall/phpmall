<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Region;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalRegionListResponse')]
class RegionListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(
        property: 'items',
        description: '地区列表',
        type: 'array',
        items: new OA\Items(ref: RegionResponse::class)
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
