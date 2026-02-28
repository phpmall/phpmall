<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Responses\GoodsAttr;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsAttrResponse')]
class GoodsAttrResponse
{
    use DTOHelper;

    #[OA\Property(property: 'goodsAttrId', description: '', type: 'integer')]
    private int $goodsAttrId;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'attrId', description: '属性ID', type: 'integer')]
    private int $attrId;

    #[OA\Property(property: 'attrValue', description: '属性值', type: 'string')]
    private string $attrValue;

    #[OA\Property(property: 'attrPrice', description: '属性价格', type: 'string')]
    private string $attrPrice;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getGoodsAttrId(): int
    {
        return $this->goodsAttrId;
    }

    /**
     * 设置
     */
    public function setGoodsAttrId(int $goodsAttrId): void
    {
        $this->goodsAttrId = $goodsAttrId;
    }

    /**
     * 获取商品ID
     */
    public function getGoodsId(): int
    {
        return $this->goodsId;
    }

    /**
     * 设置商品ID
     */
    public function setGoodsId(int $goodsId): void
    {
        $this->goodsId = $goodsId;
    }

    /**
     * 获取属性ID
     */
    public function getAttrId(): int
    {
        return $this->attrId;
    }

    /**
     * 设置属性ID
     */
    public function setAttrId(int $attrId): void
    {
        $this->attrId = $attrId;
    }

    /**
     * 获取属性值
     */
    public function getAttrValue(): string
    {
        return $this->attrValue;
    }

    /**
     * 设置属性值
     */
    public function setAttrValue(string $attrValue): void
    {
        $this->attrValue = $attrValue;
    }

    /**
     * 获取属性价格
     */
    public function getAttrPrice(): string
    {
        return $this->attrPrice;
    }

    /**
     * 设置属性价格
     */
    public function setAttrPrice(string $attrPrice): void
    {
        $this->attrPrice = $attrPrice;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedTime(): string
    {
        return $this->createdTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedTime(string $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedTime(): string
    {
        return $this->updatedTime;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedTime(string $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}
