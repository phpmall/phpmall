<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CategoryProductAttributeEntity')]
class CategoryProductAttributeEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'category_id', description: '商品分类id', type: 'integer')]
    protected int $categoryId;

    #[OA\Property(property: 'product_attribute_id', description: '商品属性id', type: 'integer')]
    protected int $productAttributeId;

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
     * 获取商品分类id
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * 设置商品分类id
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * 获取商品属性id
     */
    public function getProductAttributeId(): int
    {
        return $this->productAttributeId;
    }

    /**
     * 设置商品属性id
     */
    public function setProductAttributeId(int $productAttributeId): void
    {
        $this->productAttributeId = $productAttributeId;
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
