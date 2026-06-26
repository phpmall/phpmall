<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Category;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerCategoryTreeResponse')]
class CategoryTreeResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '分类ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parent_id', description: '父分类ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'name', description: '分类名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'icon', description: '分类图标', type: 'string', nullable: true)]
    private ?string $icon;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0禁用,1启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'children', description: '子分类', type: 'array', items: new OA\Items(ref: self::class))]
    private array $children = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
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

    public function getChildren(): array
    {
        return $this->children;
    }

    public function setChildren(array $children): void
    {
        $this->children = $children;
    }
}
