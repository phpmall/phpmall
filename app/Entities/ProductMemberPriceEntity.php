<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductMemberPriceEntity')]
class ProductMemberPriceEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'product_id', description: '商品id', type: 'integer')]
    protected int $product_id;

    #[OA\Property(property: 'member_level_id', description: '会员等级id', type: 'integer')]
    protected int $member_level_id;

    #[OA\Property(property: 'member_level_name', description: '会员等级名称', type: 'string')]
    protected string $member_level_name;

    #[OA\Property(property: 'member_discount', description: '会员折扣', type: 'float')]
    protected float $member_discount;

    #[OA\Property(property: 'member_price', description: '会员价格', type: 'float')]
    protected float $member_price;

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
     * 获取会员等级id
     */
    public function getMemberLevelId(): int
    {
        return $this->member_level_id;
    }

    /**
     * 设置会员等级id
     */
    public function setMemberLevelId(int $member_level_id): void
    {
        $this->member_level_id = $member_level_id;
    }

    /**
     * 获取会员等级名称
     */
    public function getMemberLevelName(): string
    {
        return $this->member_level_name;
    }

    /**
     * 设置会员等级名称
     */
    public function setMemberLevelName(string $member_level_name): void
    {
        $this->member_level_name = $member_level_name;
    }

    /**
     * 获取会员折扣
     */
    public function getMemberDiscount(): float
    {
        return $this->member_discount;
    }

    /**
     * 设置会员折扣
     */
    public function setMemberDiscount(float $member_discount): void
    {
        $this->member_discount = $member_discount;
    }

    /**
     * 获取会员价格
     */
    public function getMemberPrice(): float
    {
        return $this->member_price;
    }

    /**
     * 设置会员价格
     */
    public function setMemberPrice(float $member_price): void
    {
        $this->member_price = $member_price;
    }
}
