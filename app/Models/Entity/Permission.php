<?php

declare(strict_types=1);

namespace App\Models\Entity;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PermissionSchema')]
class Permission
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'type', description: '模块类型', type: 'int')]
    protected int $type;

    #[OA\Property(property: 'parent_id', description: '父级ID', type: 'int')]
    protected int $parentId;

    #[OA\Property(property: 'icon', description: '权限图标', type: 'string')]
    protected string $icon;

    #[OA\Property(property: 'name', description: '权限名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'description', description: '权限描述', type: 'string')]
    protected string $description;

    #[OA\Property(property: 'code', description: '权限路由', type: 'string')]
    protected string $code;

    #[OA\Property(property: 'menu', description: '是否为菜单项：1是,0否', type: 'int')]
    protected int $menu;

    #[OA\Property(property: 'sort', description: '权限排序', type: 'int')]
    protected int $sort;

    #[OA\Property(property: 'status', description: '状态:1正常;2禁用', type: 'int')]
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
     * 获取模块类型
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置模块类型
     */
    public function setType(int $type): void
    {
        $this->type = $type;
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
     * 获取权限图标
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * 设置权限图标
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * 获取权限名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置权限名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取权限描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置权限描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取权限路由
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置权限路由
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取是否为菜单项：1是,0否
     */
    public function getMenu(): int
    {
        return $this->menu;
    }

    /**
     * 设置是否为菜单项：1是,0否
     */
    public function setMenu(int $menu): void
    {
        $this->menu = $menu;
    }

    /**
     * 获取权限排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置权限排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取状态:1正常;2禁用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1正常;2禁用
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
