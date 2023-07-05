<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Focite\Builder\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductAttributeValueSchema')]
class ProductAttributeValue
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'product_id', description: '商品id', type: 'int')]
    protected int $productId;

    #[OA\Property(property: 'product_attribute_id', description: '商品属性id', type: 'int')]
    protected int $productAttributeId;

    #[OA\Property(property: 'value', description: '手动添加规格或参数的值，参数单值，规格有多个时以逗号隔开', type: 'string')]
    protected string $value;

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
     * 获取商品id
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * 设置商品id
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
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
     * 获取手动添加规格或参数的值，参数单值，规格有多个时以逗号隔开
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * 设置手动添加规格或参数的值，参数单值，规格有多个时以逗号隔开
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
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
