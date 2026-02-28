<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsProductEntity')]
class GoodsProductEntity
{
    use DTOHelper;

    const string getProductId = 'product_id';

    const string getGoodsId = 'goods_id'; // 商品ID

    const string getGoodsAttr = 'goods_attr'; // 商品属性

    const string getProductSn = 'product_sn'; // 货号

    const string getProductNumber = 'product_number'; // 库存数量

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'productId', description: '', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'goodsAttr', description: '商品属性', type: 'string')]
    private string $goodsAttr;

    #[OA\Property(property: 'productSn', description: '货号', type: 'string')]
    private string $productSn;

    #[OA\Property(property: 'productNumber', description: '库存数量', type: 'integer')]
    private int $productNumber;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * 设置
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
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
     * 获取商品属性
     */
    public function getGoodsAttr(): string
    {
        return $this->goodsAttr;
    }

    /**
     * 设置商品属性
     */
    public function setGoodsAttr(string $goodsAttr): void
    {
        $this->goodsAttr = $goodsAttr;
    }

    /**
     * 获取货号
     */
    public function getProductSn(): string
    {
        return $this->productSn;
    }

    /**
     * 设置货号
     */
    public function setProductSn(string $productSn): void
    {
        $this->productSn = $productSn;
    }

    /**
     * 获取库存数量
     */
    public function getProductNumber(): int
    {
        return $this->productNumber;
    }

    /**
     * 设置库存数量
     */
    public function setProductNumber(int $productNumber): void
    {
        $this->productNumber = $productNumber;
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
