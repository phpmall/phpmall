<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Notification;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'NotificationListResponse')]
class NotificationListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(
        property: 'items',
        description: '通知列表',
        type: 'array',
        items: new OA\Items(ref: NotificationResponse::class)
    )]
    private array $items;

    #[OA\Property(property: 'unread_count', description: '未读通知数量', type: 'integer')]
    private int $unreadCount;

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

    public function getUnreadCount(): int
    {
        return $this->unreadCount;
    }

    public function setUnreadCount(int $unreadCount): void
    {
        $this->unreadCount = $unreadCount;
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
