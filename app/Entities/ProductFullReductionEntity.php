<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductFullReductionEntity')]
class ProductFullReductionEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'product_id', description: '商品id', type: 'integer')]
    protected int $productId;

    #[OA\Property(property: 'full_price', description: '商品满足金额', type: 'float')]
    protected float $fullPrice;

    #[OA\Property(property: 'reduce_price', description: '商品减少金额', type: 'float')]
    protected float $reducePrice;

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
     * 获取商品满足金额
     */
    public function getFullPrice(): float
    {
        return $this->fullPrice;
    }

    /**
     * 设置商品满足金额
     */
    public function setFullPrice(float $fullPrice): void
    {
        $this->fullPrice = $fullPrice;
    }

    /**
     * 获取商品减少金额
     */
    public function getReducePrice(): float
    {
        return $this->reducePrice;
    }

    /**
     * 设置商品减少金额
     */
    public function setReducePrice(float $reducePrice): void
    {
        $this->reducePrice = $reducePrice;
    }
}
