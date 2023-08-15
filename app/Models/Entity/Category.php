<?php

declare(strict_types=1);

namespace App\Models\Entity;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CategorySchema')]
class Category
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'parent_id', description: '上级分类的编号：0表示一级分类', type: 'int')]
    protected int $parentId;

    #[OA\Property(property: 'name', description: '分类名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'icon', description: '图标', type: 'string')]
    protected string $icon;

    #[OA\Property(property: 'keywords', description: '关键字', type: 'string')]
    protected string $keywords;

    #[OA\Property(property: 'description', description: '描述', type: 'string')]
    protected string $description;

    #[OA\Property(property: 'level', description: '分类级别：0->1级；1->2级', type: 'int')]
    protected int $level;

    #[OA\Property(property: 'product_count', description: '商品数量', type: 'int')]
    protected int $productCount;

    #[OA\Property(property: 'product_unit', description: '商品单位', type: 'string')]
    protected string $productUnit;

    #[OA\Property(property: 'nav_status', description: '是否显示在导航栏：0->不显示；1->显示', type: 'int')]
    protected int $navStatus;

    #[OA\Property(property: 'show_status', description: '显示状态：0->不显示；1->显示', type: 'int')]
    protected int $showStatus;

    #[OA\Property(property: 'sort', description: '排序', type: 'int')]
    protected int $sort;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deletedAt;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取上级分类的编号：0表示一级分类
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置上级分类的编号：0表示一级分类
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取分类名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置分类名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取图标
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * 设置图标
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * 获取关键字
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * 设置关键字
     */
    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * 获取描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取分类级别：0->1级；1->2级
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * 设置分类级别：0->1级；1->2级
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * 获取商品数量
     */
    public function getProductCount(): int
    {
        return $this->productCount;
    }

    /**
     * 设置商品数量
     */
    public function setProductCount(int $productCount): void
    {
        $this->productCount = $productCount;
    }

    /**
     * 获取商品单位
     */
    public function getProductUnit(): string
    {
        return $this->productUnit;
    }

    /**
     * 设置商品单位
     */
    public function setProductUnit(string $productUnit): void
    {
        $this->productUnit = $productUnit;
    }

    /**
     * 获取是否显示在导航栏：0->不显示；1->显示
     */
    public function getNavStatus(): int
    {
        return $this->navStatus;
    }

    /**
     * 设置是否显示在导航栏：0->不显示；1->显示
     */
    public function setNavStatus(int $navStatus): void
    {
        $this->navStatus = $navStatus;
    }

    /**
     * 获取显示状态：0->不显示；1->显示
     */
    public function getShowStatus(): int
    {
        return $this->showStatus;
    }

    /**
     * 设置显示状态：0->不显示；1->显示
     */
    public function setShowStatus(int $showStatus): void
    {
        $this->showStatus = $showStatus;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
