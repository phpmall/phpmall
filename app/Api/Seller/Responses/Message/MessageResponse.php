<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Message;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerMessageResponse')]
class MessageResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '消息ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '消息类型:1系统通知,2订单通知,3活动通知', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'title', description: '消息标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '消息内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'is_read', description: '是否已读:0未读,1已读', type: 'integer')]
    private int $isRead;

    #[OA\Property(property: 'read_at', description: '阅读时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $readAt;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getIsRead(): int
    {
        return $this->isRead;
    }

    public function setIsRead(int $isRead): void
    {
        $this->isRead = $isRead;
    }

    public function getReadAt(): ?string
    {
        return $this->readAt;
    }

    public function setReadAt(?string $readAt): void
    {
        $this->readAt = $readAt;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
