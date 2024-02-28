<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductTypeEntity')]
class ProductTypeEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'name', description: '名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'attribute_count', description: '属性数量', type: 'integer')]
    protected int $attribute_count;

    #[OA\Property(property: 'param_count', description: '参数数量', type: 'integer')]
    protected int $param_count;

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
        return $this->attribute_count;
    }

    /**
     * 设置属性数量
     */
    public function setAttributeCount(int $attribute_count): void
    {
        $this->attribute_count = $attribute_count;
    }

    /**
     * 获取参数数量
     */
    public function getParamCount(): int
    {
        return $this->param_count;
    }

    /**
     * 设置参数数量
     */
    public function setParamCount(int $param_count): void
    {
        $this->param_count = $param_count;
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
