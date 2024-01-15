<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RoleEntity')]
class RoleEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'module', description: '模块名', type: 'string')]
    protected string $module;

    #[OA\Property(property: 'name', description: '角色名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'code', description: '角色编码', type: 'string')]
    protected string $code;

    #[OA\Property(property: 'description', description: '角色描述', type: 'string')]
    protected string $description;

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
     * 获取角色名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置角色名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取角色编码
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置角色编码
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取角色描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置角色描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
