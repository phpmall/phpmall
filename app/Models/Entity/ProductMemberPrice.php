<?php

declare(strict_types=1);

namespace App\Models\Entity;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductMemberPriceSchema')]
class ProductMemberPrice
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'product_id', description: '商品id', type: 'int')]
    protected int $productId;

    #[OA\Property(property: 'member_level_id', description: '会员等级id', type: 'int')]
    protected int $memberLevelId;

    #[OA\Property(property: 'member_level_name', description: '会员等级名称', type: 'string')]
    protected string $memberLevelName;

    #[OA\Property(property: 'member_discount', description: '会员折扣', type: 'float')]
    protected float $memberDiscount;

    #[OA\Property(property: 'member_price', description: '会员价格', type: 'float')]
    protected float $memberPrice;

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
     * 获取会员等级id
     */
    public function getMemberLevelId(): int
    {
        return $this->memberLevelId;
    }

    /**
     * 设置会员等级id
     */
    public function setMemberLevelId(int $memberLevelId): void
    {
        $this->memberLevelId = $memberLevelId;
    }

    /**
     * 获取会员等级名称
     */
    public function getMemberLevelName(): string
    {
        return $this->memberLevelName;
    }

    /**
     * 设置会员等级名称
     */
    public function setMemberLevelName(string $memberLevelName): void
    {
        $this->memberLevelName = $memberLevelName;
    }

    /**
     * 获取会员折扣
     */
    public function getMemberDiscount(): float
    {
        return $this->memberDiscount;
    }

    /**
     * 设置会员折扣
     */
    public function setMemberDiscount(float $memberDiscount): void
    {
        $this->memberDiscount = $memberDiscount;
    }

    /**
     * 获取会员价格
     */
    public function getMemberPrice(): float
    {
        return $this->memberPrice;
    }

    /**
     * 设置会员价格
     */
    public function setMemberPrice(float $memberPrice): void
    {
        $this->memberPrice = $memberPrice;
    }
}
