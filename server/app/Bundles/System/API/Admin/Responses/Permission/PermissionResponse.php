<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Responses\Permission;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PermissionResponse')]
class PermissionResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'module', description: '模块名:如manager,merchant', type: 'string')]
    private string $module;

    #[OA\Property(property: 'icon', description: '菜单图标', type: 'string')]
    private string $icon;

    #[OA\Property(property: 'name', description: '资源名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'resource', description: '资源标识', type: 'string')]
    private string $resource;

    #[OA\Property(property: 'menu', description: '是否为菜单项:1是,0否', type: 'integer')]
    private int $menu;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'createdAt', description: '', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '', type: 'string')]
    private string $deletedAt;

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
