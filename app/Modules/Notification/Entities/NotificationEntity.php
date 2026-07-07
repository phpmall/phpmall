<?php

declare(strict_types=1);

namespace App\Modules\Notification\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'NotificationEntity')]
class NotificationEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id';

    public const string getSenderId = 'sender_id';

    public const string getSenderType = 'sender_type';

    public const string getType = 'type';

    public const string getTitle = 'title';

    public const string getContent = 'content';

    public const string getPriority = 'priority';

    public const string getPublishAt = 'publish_at';

    public const string getExpireAt = 'expire_at';

    public const string getStatus = 'status';

    public const string getViewCount = 'view_count';

    public const string getCreatedAt = 'created_at';

    public const string getUpdatedAt = 'updated_at';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'senderId', description: '发送者ID', type: 'integer', nullable: true)]
    private ?int $senderId;

    #[OA\Property(property: 'senderType', description: '发送者类型', type: 'string', nullable: true)]
    private ?string $senderType;

    #[OA\Property(property: 'type', description: '通知类型', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'title', description: '标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'priority', description: '优先级', type: 'integer')]
    private int $priority;

    #[OA\Property(property: 'publishAt', description: '发布时间', type: 'string', nullable: true)]
    private ?string $publishAt;

    #[OA\Property(property: 'expireAt', description: '过期时间', type: 'string', nullable: true)]
    private ?string $expireAt;

    #[OA\Property(property: 'status', description: '状态', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'viewCount', description: '浏览次数', type: 'integer')]
    private int $viewCount;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSenderId(): ?int
    {
        return $this->senderId;
    }

    public function setSenderId(?int $senderId): void
    {
        $this->senderId = $senderId;
    }

    public function getSenderType(): ?string
    {
        return $this->senderType;
    }

    public function setSenderType(?string $senderType): void
    {
        $this->senderType = $senderType;
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

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getPublishAt(): ?string
    {
        return $this->publishAt;
    }

    public function setPublishAt(?string $publishAt): void
    {
        $this->publishAt = $publishAt;
    }

    public function getExpireAt(): ?string
    {
        return $this->expireAt;
    }

    public function setExpireAt(?string $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): void
    {
        $this->viewCount = $viewCount;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
