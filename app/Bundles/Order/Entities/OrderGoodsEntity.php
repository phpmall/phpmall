<?php

declare(strict_types=1);

namespace App\Bundles\Order\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderGoodsEntity')]
class OrderGoodsEntity
{
    use DTOHelper;

    const string getRecId = 'rec_id';

    const string getOrderId = 'order_id'; // 订单ID

    const string getGoodsId = 'goods_id'; // 商品ID

    const string getGoodsName = 'goods_name'; // 商品名称

    const string getGoodsSn = 'goods_sn'; // 商品编号

    const string getProductId = 'product_id'; // 货品ID

    const string getGoodsNumber = 'goods_number'; // 商品数量

    const string getMarketPrice = 'market_price'; // 市场价格

    const string getGoodsPrice = 'goods_price'; // 商品价格

    const string getGoodsAttr = 'goods_attr'; // 商品属性

    const string getSendNumber = 'send_number'; // 发货数量

    const string getIsReal = 'is_real'; // 是否实物

    const string getExtensionCode = 'extension_code'; // 扩展代码

    const string getParentId = 'parent_id'; // 父级ID

    const string getIsGift = 'is_gift'; // 是否赠品

    const string getGoodsAttrId = 'goods_attr_id'; // 商品属性ID

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'recId', description: '', type: 'integer')]
    private int $recId;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'goodsName', description: '商品名称', type: 'string')]
    private string $goodsName;

    #[OA\Property(property: 'goodsSn', description: '商品编号', type: 'string')]
    private string $goodsSn;

    #[OA\Property(property: 'productId', description: '货品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'goodsNumber', description: '商品数量', type: 'integer')]
    private int $goodsNumber;

    #[OA\Property(property: 'marketPrice', description: '市场价格', type: 'string')]
    private string $marketPrice;

    #[OA\Property(property: 'goodsPrice', description: '商品价格', type: 'string')]
    private string $goodsPrice;

    #[OA\Property(property: 'goodsAttr', description: '商品属性', type: 'string')]
    private string $goodsAttr;

    #[OA\Property(property: 'sendNumber', description: '发货数量', type: 'integer')]
    private int $sendNumber;

    #[OA\Property(property: 'isReal', description: '是否实物', type: 'integer')]
    private int $isReal;

    #[OA\Property(property: 'extensionCode', description: '扩展代码', type: 'string')]
    private string $extensionCode;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'isGift', description: '是否赠品', type: 'integer')]
    private int $isGift;

    #[OA\Property(property: 'goodsAttrId', description: '商品属性ID', type: 'string')]
    private string $goodsAttrId;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getRecId(): int
    {
        return $this->recId;
    }

    /**
     * 设置
     */
    public function setRecId(int $recId): void
    {
        $this->recId = $recId;
    }

    /**
     * 获取订单ID
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置订单ID
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
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
     * 获取商品名称
     */
    public function getGoodsName(): string
    {
        return $this->goodsName;
    }

    /**
     * 设置商品名称
     */
    public function setGoodsName(string $goodsName): void
    {
        $this->goodsName = $goodsName;
    }

    /**
     * 获取商品编号
     */
    public function getGoodsSn(): string
    {
        return $this->goodsSn;
    }

    /**
     * 设置商品编号
     */
    public function setGoodsSn(string $goodsSn): void
    {
        $this->goodsSn = $goodsSn;
    }

    /**
     * 获取货品ID
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * 设置货品ID
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * 获取商品数量
     */
    public function getGoodsNumber(): int
    {
        return $this->goodsNumber;
    }

    /**
     * 设置商品数量
     */
    public function setGoodsNumber(int $goodsNumber): void
    {
        $this->goodsNumber = $goodsNumber;
    }

    /**
     * 获取市场价格
     */
    public function getMarketPrice(): string
    {
        return $this->marketPrice;
    }

    /**
     * 设置市场价格
     */
    public function setMarketPrice(string $marketPrice): void
    {
        $this->marketPrice = $marketPrice;
    }

    /**
     * 获取商品价格
     */
    public function getGoodsPrice(): string
    {
        return $this->goodsPrice;
    }

    /**
     * 设置商品价格
     */
    public function setGoodsPrice(string $goodsPrice): void
    {
        $this->goodsPrice = $goodsPrice;
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
     * 获取发货数量
     */
    public function getSendNumber(): int
    {
        return $this->sendNumber;
    }

    /**
     * 设置发货数量
     */
    public function setSendNumber(int $sendNumber): void
    {
        $this->sendNumber = $sendNumber;
    }

    /**
     * 获取是否实物
     */
    public function getIsReal(): int
    {
        return $this->isReal;
    }

    /**
     * 设置是否实物
     */
    public function setIsReal(int $isReal): void
    {
        $this->isReal = $isReal;
    }

    /**
     * 获取扩展代码
     */
    public function getExtensionCode(): string
    {
        return $this->extensionCode;
    }

    /**
     * 设置扩展代码
     */
    public function setExtensionCode(string $extensionCode): void
    {
        $this->extensionCode = $extensionCode;
    }

    /**
     * 获取父级ID
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取是否赠品
     */
    public function getIsGift(): int
    {
        return $this->isGift;
    }

    /**
     * 设置是否赠品
     */
    public function setIsGift(int $isGift): void
    {
        $this->isGift = $isGift;
    }

    /**
     * 获取商品属性ID
     */
    public function getGoodsAttrId(): string
    {
        return $this->goodsAttrId;
    }

    /**
     * 设置商品属性ID
     */
    public function setGoodsAttrId(string $goodsAttrId): void
    {
        $this->goodsAttrId = $goodsAttrId;
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
