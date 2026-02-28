<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Responses\Goods;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsResponse')]
class GoodsResponse
{
    use DTOHelper;

    #[OA\Property(property: 'goodsId', description: '', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'catId', description: '商品分类ID', type: 'integer')]
    private int $catId;

    #[OA\Property(property: 'goodsSn', description: '商品编码', type: 'string')]
    private string $goodsSn;

    #[OA\Property(property: 'goodsName', description: '商品名称', type: 'string')]
    private string $goodsName;

    #[OA\Property(property: 'goodsNameStyle', description: '商品名称样式', type: 'string')]
    private string $goodsNameStyle;

    #[OA\Property(property: 'clickCount', description: '点击次数', type: 'integer')]
    private int $clickCount;

    #[OA\Property(property: 'brandId', description: '商品品牌ID', type: 'integer')]
    private int $brandId;

    #[OA\Property(property: 'providerName', description: '供应商名称', type: 'string')]
    private string $providerName;

    #[OA\Property(property: 'goodsNumber', description: '商品库存', type: 'integer')]
    private int $goodsNumber;

    #[OA\Property(property: 'goodsWeight', description: '商品重量', type: 'string')]
    private string $goodsWeight;

    #[OA\Property(property: 'marketPrice', description: '市场价格', type: 'string')]
    private string $marketPrice;

    #[OA\Property(property: 'shopPrice', description: '商城价格', type: 'string')]
    private string $shopPrice;

    #[OA\Property(property: 'promotePrice', description: '促销价格', type: 'string')]
    private string $promotePrice;

    #[OA\Property(property: 'promoteStartDate', description: '促销开始时间', type: 'integer')]
    private int $promoteStartDate;

    #[OA\Property(property: 'promoteEndDate', description: '促销结束时间', type: 'integer')]
    private int $promoteEndDate;

    #[OA\Property(property: 'warnNumber', description: '库存警告数量', type: 'integer')]
    private int $warnNumber;

    #[OA\Property(property: 'keywords', description: '关键词', type: 'string')]
    private string $keywords;

    #[OA\Property(property: 'goodsBrief', description: '商品简介', type: 'string')]
    private string $goodsBrief;

    #[OA\Property(property: 'goodsDesc', description: '商品描述', type: 'string')]
    private string $goodsDesc;

    #[OA\Property(property: 'goodsThumb', description: '商品缩略图', type: 'string')]
    private string $goodsThumb;

    #[OA\Property(property: 'goodsImg', description: '商品图片', type: 'string')]
    private string $goodsImg;

    #[OA\Property(property: 'originalImg', description: '商品原图', type: 'string')]
    private string $originalImg;

    #[OA\Property(property: 'isReal', description: '是否实物', type: 'integer')]
    private int $isReal;

    #[OA\Property(property: 'extensionCode', description: '扩展代码', type: 'string')]
    private string $extensionCode;

    #[OA\Property(property: 'isOnSale', description: '是否上架', type: 'integer')]
    private int $isOnSale;

    #[OA\Property(property: 'isAloneSale', description: '是否单独销售', type: 'integer')]
    private int $isAloneSale;

    #[OA\Property(property: 'isShipping', description: '是否包邮', type: 'integer')]
    private int $isShipping;

    #[OA\Property(property: 'integral', description: '积分', type: 'integer')]
    private int $integral;

    #[OA\Property(property: 'addTime', description: '添加时间', type: 'integer')]
    private int $addTime;

    #[OA\Property(property: 'sortOrder', description: '排序', type: 'integer')]
    private int $sortOrder;

    #[OA\Property(property: 'isDelete', description: '是否删除', type: 'integer')]
    private int $isDelete;

    #[OA\Property(property: 'isBest', description: '是否精品', type: 'integer')]
    private int $isBest;

    #[OA\Property(property: 'isNew', description: '是否新品', type: 'integer')]
    private int $isNew;

    #[OA\Property(property: 'isHot', description: '是否热卖', type: 'integer')]
    private int $isHot;

    #[OA\Property(property: 'isPromote', description: '是否促销', type: 'integer')]
    private int $isPromote;

    #[OA\Property(property: 'bonusTypeId', description: '红包类型ID', type: 'integer')]
    private int $bonusTypeId;

    #[OA\Property(property: 'lastUpdate', description: '最后更新时间', type: 'integer')]
    private int $lastUpdate;

    #[OA\Property(property: 'goodsType', description: '商品类型', type: 'integer')]
    private int $goodsType;

    #[OA\Property(property: 'sellerNote', description: '商家备注', type: 'string')]
    private string $sellerNote;

    #[OA\Property(property: 'giveIntegral', description: '赠送积分', type: 'integer')]
    private int $giveIntegral;

    #[OA\Property(property: 'rankIntegral', description: '等级积分', type: 'integer')]
    private int $rankIntegral;

    #[OA\Property(property: 'suppliersId', description: '供应商ID', type: 'integer')]
    private int $suppliersId;

    #[OA\Property(property: 'isCheck', description: '是否审核', type: 'integer')]
    private int $isCheck;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getGoodsId(): int
    {
        return $this->goodsId;
    }

    /**
     * 设置
     */
    public function setGoodsId(int $goodsId): void
    {
        $this->goodsId = $goodsId;
    }

    /**
     * 获取商品分类ID
     */
    public function getCatId(): int
    {
        return $this->catId;
    }

    /**
     * 设置商品分类ID
     */
    public function setCatId(int $catId): void
    {
        $this->catId = $catId;
    }

    /**
     * 获取商品编码
     */
    public function getGoodsSn(): string
    {
        return $this->goodsSn;
    }

    /**
     * 设置商品编码
     */
    public function setGoodsSn(string $goodsSn): void
    {
        $this->goodsSn = $goodsSn;
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
     * 获取商品名称样式
     */
    public function getGoodsNameStyle(): string
    {
        return $this->goodsNameStyle;
    }

    /**
     * 设置商品名称样式
     */
    public function setGoodsNameStyle(string $goodsNameStyle): void
    {
        $this->goodsNameStyle = $goodsNameStyle;
    }

    /**
     * 获取点击次数
     */
    public function getClickCount(): int
    {
        return $this->clickCount;
    }

    /**
     * 设置点击次数
     */
    public function setClickCount(int $clickCount): void
    {
        $this->clickCount = $clickCount;
    }

    /**
     * 获取商品品牌ID
     */
    public function getBrandId(): int
    {
        return $this->brandId;
    }

    /**
     * 设置商品品牌ID
     */
    public function setBrandId(int $brandId): void
    {
        $this->brandId = $brandId;
    }

    /**
     * 获取供应商名称
     */
    public function getProviderName(): string
    {
        return $this->providerName;
    }

    /**
     * 设置供应商名称
     */
    public function setProviderName(string $providerName): void
    {
        $this->providerName = $providerName;
    }

    /**
     * 获取商品库存
     */
    public function getGoodsNumber(): int
    {
        return $this->goodsNumber;
    }

    /**
     * 设置商品库存
     */
    public function setGoodsNumber(int $goodsNumber): void
    {
        $this->goodsNumber = $goodsNumber;
    }

    /**
     * 获取商品重量
     */
    public function getGoodsWeight(): string
    {
        return $this->goodsWeight;
    }

    /**
     * 设置商品重量
     */
    public function setGoodsWeight(string $goodsWeight): void
    {
        $this->goodsWeight = $goodsWeight;
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
     * 获取商城价格
     */
    public function getShopPrice(): string
    {
        return $this->shopPrice;
    }

    /**
     * 设置商城价格
     */
    public function setShopPrice(string $shopPrice): void
    {
        $this->shopPrice = $shopPrice;
    }

    /**
     * 获取促销价格
     */
    public function getPromotePrice(): string
    {
        return $this->promotePrice;
    }

    /**
     * 设置促销价格
     */
    public function setPromotePrice(string $promotePrice): void
    {
        $this->promotePrice = $promotePrice;
    }

    /**
     * 获取促销开始时间
     */
    public function getPromoteStartDate(): int
    {
        return $this->promoteStartDate;
    }

    /**
     * 设置促销开始时间
     */
    public function setPromoteStartDate(int $promoteStartDate): void
    {
        $this->promoteStartDate = $promoteStartDate;
    }

    /**
     * 获取促销结束时间
     */
    public function getPromoteEndDate(): int
    {
        return $this->promoteEndDate;
    }

    /**
     * 设置促销结束时间
     */
    public function setPromoteEndDate(int $promoteEndDate): void
    {
        $this->promoteEndDate = $promoteEndDate;
    }

    /**
     * 获取库存警告数量
     */
    public function getWarnNumber(): int
    {
        return $this->warnNumber;
    }

    /**
     * 设置库存警告数量
     */
    public function setWarnNumber(int $warnNumber): void
    {
        $this->warnNumber = $warnNumber;
    }

    /**
     * 获取关键词
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * 设置关键词
     */
    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * 获取商品简介
     */
    public function getGoodsBrief(): string
    {
        return $this->goodsBrief;
    }

    /**
     * 设置商品简介
     */
    public function setGoodsBrief(string $goodsBrief): void
    {
        $this->goodsBrief = $goodsBrief;
    }

    /**
     * 获取商品描述
     */
    public function getGoodsDesc(): string
    {
        return $this->goodsDesc;
    }

    /**
     * 设置商品描述
     */
    public function setGoodsDesc(string $goodsDesc): void
    {
        $this->goodsDesc = $goodsDesc;
    }

    /**
     * 获取商品缩略图
     */
    public function getGoodsThumb(): string
    {
        return $this->goodsThumb;
    }

    /**
     * 设置商品缩略图
     */
    public function setGoodsThumb(string $goodsThumb): void
    {
        $this->goodsThumb = $goodsThumb;
    }

    /**
     * 获取商品图片
     */
    public function getGoodsImg(): string
    {
        return $this->goodsImg;
    }

    /**
     * 设置商品图片
     */
    public function setGoodsImg(string $goodsImg): void
    {
        $this->goodsImg = $goodsImg;
    }

    /**
     * 获取商品原图
     */
    public function getOriginalImg(): string
    {
        return $this->originalImg;
    }

    /**
     * 设置商品原图
     */
    public function setOriginalImg(string $originalImg): void
    {
        $this->originalImg = $originalImg;
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
     * 获取是否上架
     */
    public function getIsOnSale(): int
    {
        return $this->isOnSale;
    }

    /**
     * 设置是否上架
     */
    public function setIsOnSale(int $isOnSale): void
    {
        $this->isOnSale = $isOnSale;
    }

    /**
     * 获取是否单独销售
     */
    public function getIsAloneSale(): int
    {
        return $this->isAloneSale;
    }

    /**
     * 设置是否单独销售
     */
    public function setIsAloneSale(int $isAloneSale): void
    {
        $this->isAloneSale = $isAloneSale;
    }

    /**
     * 获取是否包邮
     */
    public function getIsShipping(): int
    {
        return $this->isShipping;
    }

    /**
     * 设置是否包邮
     */
    public function setIsShipping(int $isShipping): void
    {
        $this->isShipping = $isShipping;
    }

    /**
     * 获取积分
     */
    public function getIntegral(): int
    {
        return $this->integral;
    }

    /**
     * 设置积分
     */
    public function setIntegral(int $integral): void
    {
        $this->integral = $integral;
    }

    /**
     * 获取添加时间
     */
    public function getAddTime(): int
    {
        return $this->addTime;
    }

    /**
     * 设置添加时间
     */
    public function setAddTime(int $addTime): void
    {
        $this->addTime = $addTime;
    }

    /**
     * 获取排序
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * 设置排序
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * 获取是否删除
     */
    public function getIsDelete(): int
    {
        return $this->isDelete;
    }

    /**
     * 设置是否删除
     */
    public function setIsDelete(int $isDelete): void
    {
        $this->isDelete = $isDelete;
    }

    /**
     * 获取是否精品
     */
    public function getIsBest(): int
    {
        return $this->isBest;
    }

    /**
     * 设置是否精品
     */
    public function setIsBest(int $isBest): void
    {
        $this->isBest = $isBest;
    }

    /**
     * 获取是否新品
     */
    public function getIsNew(): int
    {
        return $this->isNew;
    }

    /**
     * 设置是否新品
     */
    public function setIsNew(int $isNew): void
    {
        $this->isNew = $isNew;
    }

    /**
     * 获取是否热卖
     */
    public function getIsHot(): int
    {
        return $this->isHot;
    }

    /**
     * 设置是否热卖
     */
    public function setIsHot(int $isHot): void
    {
        $this->isHot = $isHot;
    }

    /**
     * 获取是否促销
     */
    public function getIsPromote(): int
    {
        return $this->isPromote;
    }

    /**
     * 设置是否促销
     */
    public function setIsPromote(int $isPromote): void
    {
        $this->isPromote = $isPromote;
    }

    /**
     * 获取红包类型ID
     */
    public function getBonusTypeId(): int
    {
        return $this->bonusTypeId;
    }

    /**
     * 设置红包类型ID
     */
    public function setBonusTypeId(int $bonusTypeId): void
    {
        $this->bonusTypeId = $bonusTypeId;
    }

    /**
     * 获取最后更新时间
     */
    public function getLastUpdate(): int
    {
        return $this->lastUpdate;
    }

    /**
     * 设置最后更新时间
     */
    public function setLastUpdate(int $lastUpdate): void
    {
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * 获取商品类型
     */
    public function getGoodsType(): int
    {
        return $this->goodsType;
    }

    /**
     * 设置商品类型
     */
    public function setGoodsType(int $goodsType): void
    {
        $this->goodsType = $goodsType;
    }

    /**
     * 获取商家备注
     */
    public function getSellerNote(): string
    {
        return $this->sellerNote;
    }

    /**
     * 设置商家备注
     */
    public function setSellerNote(string $sellerNote): void
    {
        $this->sellerNote = $sellerNote;
    }

    /**
     * 获取赠送积分
     */
    public function getGiveIntegral(): int
    {
        return $this->giveIntegral;
    }

    /**
     * 设置赠送积分
     */
    public function setGiveIntegral(int $giveIntegral): void
    {
        $this->giveIntegral = $giveIntegral;
    }

    /**
     * 获取等级积分
     */
    public function getRankIntegral(): int
    {
        return $this->rankIntegral;
    }

    /**
     * 设置等级积分
     */
    public function setRankIntegral(int $rankIntegral): void
    {
        $this->rankIntegral = $rankIntegral;
    }

    /**
     * 获取供应商ID
     */
    public function getSuppliersId(): int
    {
        return $this->suppliersId;
    }

    /**
     * 设置供应商ID
     */
    public function setSuppliersId(int $suppliersId): void
    {
        $this->suppliersId = $suppliersId;
    }

    /**
     * 获取是否审核
     */
    public function getIsCheck(): int
    {
        return $this->isCheck;
    }

    /**
     * 设置是否审核
     */
    public function setIsCheck(int $isCheck): void
    {
        $this->isCheck = $isCheck;
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
