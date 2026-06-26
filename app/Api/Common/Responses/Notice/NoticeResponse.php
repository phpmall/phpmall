<?php

declare(strict_types=1);

namespace App\Api\Common\Responses\Notice;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommonNoticeResponse')]
class NoticeResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '公告ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'title', description: '公告标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '公告内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'type', description: '公告类型', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'is_top', description: '是否置顶:0否,1是', type: 'integer')]
    private int $isTop;

    #[OA\Property(property: 'view_count', description: '浏览次数', type: 'integer')]
    private int $viewCount;

    #[OA\Property(property: 'published_at', description: '发布时间', type: 'string', format: 'date-time')]
    private string $publishedAt;

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

    public function getPublishedAt(): string
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(string $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
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
