<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Focite\Builder\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RoleSchema')]
class Role
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'type', description: '模块类型', type: 'int')]
    protected int $type;

    #[OA\Property(property: 'name', description: '角色名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'description', description: '角色描述', type: 'string')]
    protected string $description;

    #[OA\Property(property: 'sort', description: '角色排序', type: 'int')]
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
     * 获取角色排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置角色排序
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
