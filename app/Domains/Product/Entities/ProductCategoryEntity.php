<?php

declare(strict_types=1);

namespace App\Domains\Product\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductCategoryEntity')]
class ProductCategoryEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getParentId = 'parent_id'; // 父分类ID，0=根

    public const string getName = 'name'; // 分类名称

    public const string getIconUrl = 'icon_url'; // 图标

    public const string getSortOrder = 'sort_order'; // 排序

    public const string getIsShow = 'is_show'; // 是否显示：0-否，1-是

    public const string getLevel = 'level'; // 层级：1-一级，2-二级，3-三级

    public const string getPath = 'path'; // 层级路径

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentId', description: '父分类ID，0=根', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'name', description: '分类名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'iconUrl', description: '图标', type: 'string')]
    private string $iconUrl;

    #[OA\Property(property: 'sortOrder', description: '排序', type: 'integer')]
    private int $sortOrder;

    #[OA\Property(property: 'isShow', description: '是否显示：0-否，1-是', type: 'integer')]
    private int $isShow;

    #[OA\Property(property: 'level', description: '层级：1-一级，2-二级，3-三级', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'path', description: '层级路径', type: 'string')]
    private string $path;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取父分类ID，0=根
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父分类ID，0=根
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
    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }

    /**
     * 设置图标
     */
    public function setIconUrl(string $iconUrl): void
    {
        $this->iconUrl = $iconUrl;
    }

    /**
     * 获取排序
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * 设置排序
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * 获取是否显示：0-否，1-是
     */
    public function getIsShow(): int
    {
        return $this->isShow;
    }

    /**
     * 设置是否显示：0-否，1-是
     */
    public function setIsShow(int $isShow): void
    {
        $this->isShow = $isShow;
    }

    /**
     * 获取层级：1-一级，2-二级，3-三级
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * 设置层级：1-一级，2-二级，3-三级
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * 获取层级路径
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * 设置层级路径
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
