<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductFullReductionEntity')]
class ProductFullReductionEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'product_id', description: '商品id', type: 'integer')]
    protected int $product_id;

    #[OA\Property(property: 'full_price', description: '商品满足金额', type: 'float')]
    protected float $full_price;

    #[OA\Property(property: 'reduce_price', description: '商品减少金额', type: 'float')]
    protected float $reduce_price;

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
     * 获取商品满足金额
     */
    public function getFullPrice(): float
    {
        return $this->full_price;
    }

    /**
     * 设置商品满足金额
     */
    public function setFullPrice(float $full_price): void
    {
        $this->full_price = $full_price;
    }

    /**
     * 获取商品减少金额
     */
    public function getReducePrice(): float
    {
        return $this->reduce_price;
    }

    /**
     * 设置商品减少金额
     */
    public function setReducePrice(float $reduce_price): void
    {
        $this->reduce_price = $reduce_price;
    }
}
