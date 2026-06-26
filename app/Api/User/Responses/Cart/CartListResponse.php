<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Cart;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CartListResponse')]
class CartListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(
        property: 'items',
        description: '购物车商品列表',
        type: 'array',
        items: new OA\Items(ref: CartItemResponse::class)
    )]
    private array $items;

    #[OA\Property(property: 'total_count', description: '商品总数', type: 'integer')]
    private int $totalCount;

    #[OA\Property(property: 'selected_count', description: '已选中数量', type: 'integer')]
    private int $selectedCount;

    #[OA\Property(property: 'total_amount', description: '已选中商品总金额(分)', type: 'integer')]
    private int $totalAmount;

    #[OA\Property(property: 'invalid_count', description: '失效商品数量', type: 'integer')]
    private int $invalidCount;

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

    public function getSelectedCount(): int
    {
        return $this->selectedCount;
    }

    public function setSelectedCount(int $selectedCount): void
    {
        $this->selectedCount = $selectedCount;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getInvalidCount(): int
    {
        return $this->invalidCount;
    }

    public function setInvalidCount(int $invalidCount): void
    {
        $this->invalidCount = $invalidCount;
    }
}
