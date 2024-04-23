<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemPermissionEntity')]
class SystemPermissionEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'parent_id', description: '父级ID', type: 'integer')]
    protected int $parent_id;

    #[OA\Property(property: 'module', description: '模块名:如manager,merchant', type: 'string')]
    protected string $module;

    #[OA\Property(property: 'icon', description: '菜单图标', type: 'string')]
    protected string $icon;

    #[OA\Property(property: 'name', description: '资源名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'resource', description: '资源标识', type: 'string')]
    protected string $resource;

    #[OA\Property(property: 'menu', description: '是否为菜单项:1是,0否', type: 'integer')]
    protected int $menu;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '删除时间', type: 'string')]
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
     * 获取父级ID
     */
    public function getParentId(): int
    {
        return $this->parent_id;
    }

    /**
     * 设置父级ID
     */
    public function setParentId(int $parent_id): void
    {
        $this->parent_id = $parent_id;
    }

    /**
     * 获取模块名:如manager,merchant
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * 设置模块名:如manager,merchant
     */
    public function setModule(string $module): void
    {
        $this->module = $module;
    }

    /**
     * 获取菜单图标
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * 设置菜单图标
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * 获取资源名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置资源名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取资源标识
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * 设置资源标识
     */
    public function setResource(string $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * 获取是否为菜单项:1是,0否
     */
    public function getMenu(): int
    {
        return $this->menu;
    }

    /**
     * 设置是否为菜单项:1是,0否
     */
    public function setMenu(int $menu): void
    {
        $this->menu = $menu;
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
