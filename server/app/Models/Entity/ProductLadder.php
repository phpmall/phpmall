<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Focite\Builder\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductLadderSchema')]
class ProductLadder
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'product_id', description: '商品id', type: 'int')]
    protected int $productId;

    #[OA\Property(property: 'count', description: '满足的商品数量', type: 'int')]
    protected int $count;

    #[OA\Property(property: 'discount', description: '折扣', type: 'float')]
    protected float $discount;

    #[OA\Property(property: 'price', description: '折后价格', type: 'float')]
    protected float $price;

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
     * 获取满足的商品数量
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * 设置满足的商品数量
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * 获取折扣
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * 设置折扣
     */
    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * 获取折后价格
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * 设置折后价格
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
