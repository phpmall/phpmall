<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductAttributeValueEntity')]
class ProductAttributeValueEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'product_id', description: '商品id', type: 'integer')]
    protected int $product_id;

    #[OA\Property(property: 'product_attribute_id', description: '商品属性id', type: 'integer')]
    protected int $product_attribute_id;

    #[OA\Property(property: 'value', description: '手动添加规格或参数的值，参数单值，规格有多个时以逗号隔开', type: 'string')]
    protected string $value;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

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
        return $this->product_id;
    }

    /**
     * 设置商品id
     */
    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }

    /**
     * 获取商品属性id
     */
    public function getProductAttributeId(): int
    {
        return $this->product_attribute_id;
    }

    /**
     * 设置商品属性id
     */
    public function setProductAttributeId(int $product_attribute_id): void
    {
        $this->product_attribute_id = $product_attribute_id;
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
}
