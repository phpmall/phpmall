<?php

declare(strict_types=1);

namespace App\Api\Shop\Responses\Category;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopCategoryTreeResponse')]
class CategoryTreeResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '分类ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '分类名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'icon', description: '分类图标', type: 'string', nullable: true)]
    private ?string $icon;

    #[OA\Property(property: 'image', description: '分类图片', type: 'string', nullable: true)]
    private ?string $image;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'children', description: '子分类列表', type: 'array', items: new OA\Items(ref: self::class))]
    private array $children;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
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
