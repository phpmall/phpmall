<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Notification;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'NotificationResponse')]
class NotificationResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '通知ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '通知类型:system,order,promotion,activity', type: 'string')]
    private string $type;

    #[OA\Property(property: 'title', description: '通知标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '通知内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'is_read', description: '是否已读:0否，1是', type: 'integer')]
    private int $isRead;

    #[OA\Property(property: 'target_type', description: '关联对象类型', type: 'string', nullable: true)]
    private ?string $targetType;

    #[OA\Property(property: 'target_id', description: '关联对象ID', type: 'integer', nullable: true)]
    private ?int $targetId;

    #[OA\Property(property: 'created_at', description: '发送时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'read_at', description: '阅读时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $readAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
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

    public function getTargetType(): ?string
    {
        return $this->targetType;
    }

    public function setTargetType(?string $targetType): void
    {
        $this->targetType = $targetType;
    }

    public function getTargetId(): ?int
    {
        return $this->targetId;
    }

    public function setTargetId(?int $targetId): void
    {
        $this->targetId = $targetId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getReadAt(): ?string
    {
        return $this->readAt;
    }

    public function setReadAt(?string $readAt): void
    {
        $this->readAt = $readAt;
    }
}
