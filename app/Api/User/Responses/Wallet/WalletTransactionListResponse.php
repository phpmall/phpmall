<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Wallet;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WalletTransactionListResponse')]
class WalletTransactionListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(
        property: 'items',
        description: '交易记录列表',
        type: 'array',
        items: new OA\Items(ref: WalletTransactionResponse::class)
    )]
    private array $items;

    #[OA\Property(property: 'pagination', description: '分页信息', type: 'object', properties: [
        new OA\Property(property: 'page', description: '当前页码', type: 'integer'),
        new OA\Property(property: 'per_page', description: '每页数量', type: 'integer'),
        new OA\Property(property: 'total', description: '总记录数', type: 'integer'),
        new OA\Property(property: 'total_pages', description: '总页数', type: 'integer'),
        new OA\Property(property: 'has_next', description: '是否有下一页', type: 'boolean'),
        new OA\Property(property: 'has_prev', description: '是否有上一页', type: 'boolean'),
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
