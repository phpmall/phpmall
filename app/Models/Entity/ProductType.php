<?php

declare(strict_types=1);

namespace App\Models\Entity;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductTypeSchema')]
class ProductType
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'name', description: '名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'attribute_count', description: '属性数量', type: 'int')]
    protected int $attributeCount;

    #[OA\Property(property: 'param_count', description: '参数数量', type: 'int')]
    protected int $paramCount;

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
     * 获取属性数量
     */
    public function getAttributeCount(): int
    {
        return $this->attributeCount;
    }

    /**
     * 设置属性数量
     */
    public function setAttributeCount(int $attributeCount): void
    {
        $this->attributeCount = $attributeCount;
    }

    /**
     * 获取参数数量
     */
    public function getParamCount(): int
    {
        return $this->paramCount;
    }

    /**
     * 设置参数数量
     */
    public function setParamCount(int $paramCount): void
    {
        $this->paramCount = $paramCount;
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
