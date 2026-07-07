<?php

declare(strict_types=1);

namespace App\Modules\Message\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MessageEntity')]
class MessageEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id';

    public const string getUserId = 'user_id';

    public const string getType = 'type';

    public const string getTitle = 'title';

    public const string getContent = 'content';

    public const string getIsRead = 'is_read';

    public const string getReadAt = 'read_at';

    public const string getLinkUrl = 'link_url';

    public const string getExtraData = 'extra_data';

    public const string getStatus = 'status';

    public const string getCreatedAt = 'created_at';

    public const string getUpdatedAt = 'updated_at';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer', nullable: true)]
    private ?int $userId;

    #[OA\Property(property: 'type', description: '消息类型', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'title', description: '标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'isRead', description: '是否已读', type: 'integer')]
    private int $isRead;

    #[OA\Property(property: 'readAt', description: '阅读时间', type: 'string', nullable: true)]
    private ?string $readAt;

    #[OA\Property(property: 'linkUrl', description: '跳转链接', type: 'string', nullable: true)]
    private ?string $linkUrl;

    #[OA\Property(property: 'extraData', description: '扩展数据', type: 'object', nullable: true)]
    private ?array $extraData;

    #[OA\Property(property: 'status', description: '状态', type: 'integer')]
    private int $status;

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

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
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

    public function getLinkUrl(): ?string
    {
        return $this->linkUrl;
    }

    public function setLinkUrl(?string $linkUrl): void
    {
        $this->linkUrl = $linkUrl;
    }

    public function getExtraData(): ?array
    {
        return $this->extraData;
    }

    public function setExtraData(?array $extraData): void
    {
        $this->extraData = $extraData;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
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
