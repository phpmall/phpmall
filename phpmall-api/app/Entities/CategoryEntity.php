<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CategoryEntity')]
class CategoryEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'parent_id', description: '上级分类的编号：0表示一级分类', type: 'integer')]
    protected int $parent_id;

    #[OA\Property(property: 'name', description: '分类名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'icon', description: '图标', type: 'string')]
    protected string $icon;

    #[OA\Property(property: 'keywords', description: '关键字', type: 'string')]
    protected string $keywords;

    #[OA\Property(property: 'description', description: '描述', type: 'string')]
    protected string $description;

    #[OA\Property(property: 'level', description: '分类级别：0->1级；1->2级', type: 'integer')]
    protected int $level;

    #[OA\Property(property: 'product_count', description: '商品数量', type: 'integer')]
    protected int $product_count;

    #[OA\Property(property: 'product_unit', description: '商品单位', type: 'string')]
    protected string $product_unit;

    #[OA\Property(property: 'nav_status', description: '是否显示在导航栏：0->不显示；1->显示', type: 'integer')]
    protected int $nav_status;

    #[OA\Property(property: 'show_status', description: '显示状态：0->不显示；1->显示', type: 'integer')]
    protected int $show_status;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deleted_at;

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
        return $this->parent_id;
    }

    /**
     * 设置上级分类的编号：0表示一级分类
     */
    public function setParentId(int $parent_id): void
    {
        $this->parent_id = $parent_id;
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
        return $this->product_count;
    }

    /**
     * 设置商品数量
     */
    public function setProductCount(int $product_count): void
    {
        $this->product_count = $product_count;
    }

    /**
     * 获取商品单位
     */
    public function getProductUnit(): string
    {
        return $this->product_unit;
    }

    /**
     * 设置商品单位
     */
    public function setProductUnit(string $product_unit): void
    {
        $this->product_unit = $product_unit;
    }

    /**
     * 获取是否显示在导航栏：0->不显示；1->显示
     */
    public function getNavStatus(): int
    {
        return $this->nav_status;
    }

    /**
     * 设置是否显示在导航栏：0->不显示；1->显示
     */
    public function setNavStatus(int $nav_status): void
    {
        $this->nav_status = $nav_status;
    }

    /**
     * 获取显示状态：0->不显示；1->显示
     */
    public function getShowStatus(): int
    {
        return $this->show_status;
    }

    /**
     * 设置显示状态：0->不显示；1->显示
     */
    public function setShowStatus(int $show_status): void
    {
        $this->show_status = $show_status;
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
        return $this->created_at;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deleted_at;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }
}
