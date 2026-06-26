<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Banner;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalBannerResponse')]
class BannerResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '轮播图ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'title', description: '标题', type: 'string', nullable: true)]
    private ?string $title;

    #[OA\Property(property: 'image', description: '图片地址', type: 'string')]
    private string $image;

    #[OA\Property(property: 'link', description: '跳转链接', type: 'string', nullable: true)]
    private ?string $link;

    #[OA\Property(property: 'position', description: '展示位置', type: 'string')]
    private string $position;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0禁用,1启用', type: 'integer')]
    private int $status;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
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
}
