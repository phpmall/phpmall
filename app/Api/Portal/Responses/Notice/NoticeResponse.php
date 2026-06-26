<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Notice;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalNoticeResponse')]
class NoticeResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '公告ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'title', description: '公告标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '公告内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'type', description: '公告类型', type: 'integer', nullable: true)]
    private ?int $type;

    #[OA\Property(property: 'type_name', description: '类型名称', type: 'string', nullable: true)]
    private ?string $typeName;

    #[OA\Property(property: 'is_top', description: '是否置顶:0否,1是', type: 'integer')]
    private int $isTop;

    #[OA\Property(property: 'view_count', description: '浏览次数', type: 'integer')]
    private int $viewCount;

    #[OA\Property(property: 'status', description: '状态:0禁用,1启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string', format: 'date-time')]
    private string $updatedAt;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): void
    {
        $this->type = $type;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(?string $typeName): void
    {
        $this->typeName = $typeName;
    }

    public function getIsTop(): int
    {
        return $this->isTop;
    }

    public function setIsTop(int $isTop): void
    {
        $this->isTop = $isTop;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): void
    {
        $this->viewCount = $viewCount;
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
