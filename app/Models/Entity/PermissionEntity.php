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

    #[OA\Property(property: 'guard', description: '守卫模块', type: 'string')]
    protected string $guard;

    #[OA\Property(property: 'parent_id', description: '父级ID', type: 'integer')]
    protected int $parentId;

    #[OA\Property(property: 'name', description: '名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'description', description: '描述', type: 'string')]
    protected string $description;

    #[OA\Property(property: 'path', description: '标识', type: 'string')]
    protected string $path;

    #[OA\Property(property: 'icon', description: 'ICON图标', type: 'string')]
    protected string $icon;

    #[OA\Property(property: 'type', description: '类型：1菜单,2页面,3接口', type: 'integer')]
    protected int $type;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

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
     * 获取守卫模块
     */
    public function getGuard(): string
    {
        return $this->guard;
    }

    /**
     * 设置守卫模块
     */
    public function setGuard(string $guard): void
    {
        $this->guard = $guard;
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
     * 获取标识
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * 设置标识
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
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
     * 获取类型：1菜单,2页面,3接口
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置类型：1菜单,2页面,3接口
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
}
