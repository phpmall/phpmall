<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PermissionEntity')]
class PermissionEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'module', description: '模块名', type: 'string')]
    protected string $module;

    #[OA\Property(property: 'parent_id', description: '父级ID', type: 'integer')]
    protected int $parentId;

    #[OA\Property(property: 'name', description: '名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'icon', description: 'ICON图标', type: 'string')]
    protected string $icon;

    #[OA\Property(property: 'path', description: '标识规则', type: 'string')]
    protected string $path;

    #[OA\Property(property: 'tags', description: '描述标签', type: 'string')]
    protected string $tags;

    #[OA\Property(property: 'type', description: '类型：1菜单,2按钮,3接口', type: 'integer')]
    protected int $type;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

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
     * 获取模块名
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * 设置模块名
     */
    public function setModule(string $module): void
    {
        $this->module = $module;
    }

    /**
     * 获取父级ID
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取ICON图标
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * 设置ICON图标
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * 获取标识规则
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * 设置标识规则
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * 获取描述标签
     */
    public function getTags(): string
    {
        return $this->tags;
    }

    /**
     * 设置描述标签
     */
    public function setTags(string $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * 获取类型：1菜单,2按钮,3接口
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置类型：1菜单,2按钮,3接口
     */
    public function setType(int $type): void
    {
        $this->type = $type;
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
     * 获取状态:1正常,2禁用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1正常,2禁用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
