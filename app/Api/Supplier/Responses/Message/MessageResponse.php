<?php

declare(strict_types=1);

namespace App\Api\Supplier\Responses\Message;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierMessageResponse')]
class MessageResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '消息ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'title', description: '消息标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '消息内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'type', description: '消息类型', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'is_read', description: '是否已读:0未读,1已读', type: 'integer')]
    private int $isRead;

    #[OA\Property(property: 'sender_id', description: '发送者ID', type: 'integer', nullable: true)]
    private ?int $senderId;

    #[OA\Property(property: 'sender_name', description: '发送者名称', type: 'string', nullable: true)]
    private ?string $senderName;

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

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getIsRead(): int
    {
        return $this->isRead;
    }

    public function setIsRead(int $isRead): void
    {
        $this->isRead = $isRead;
    }

    public function getSenderId(): ?int
    {
        return $this->senderId;
    }

    public function setSenderId(?int $senderId): void
    {
        $this->senderId = $senderId;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function setSenderName(?string $senderName): void
    {
        $this->senderName = $senderName;
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
