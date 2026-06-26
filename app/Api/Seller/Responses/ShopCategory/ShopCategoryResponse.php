<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\ShopCategory;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerShopCategoryResponse')]
class ShopCategoryResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '分类ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shop_id', description: '店铺ID', type: 'integer')]
    private int $shopId;

    #[OA\Property(property: 'parent_id', description: '父分类ID:0为顶级', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'name', description: '分类名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'icon', description: '分类图标', type: 'string', nullable: true)]
    private ?string $icon;

    #[OA\Property(property: 'sort', description: '排序值', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'is_show', description: '是否显示:0隐藏,1显示', type: 'integer')]
    private int $isShow;

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

    public function getShopId(): int
    {
        return $this->shopId;
    }

    public function setShopId(int $shopId): void
    {
        $this->shopId = $shopId;
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

    public function getIsShow(): int
    {
        return $this->isShow;
    }

    public function setIsShow(int $isShow): void
    {
        $this->isShow = $isShow;
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
