<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Focite\Builder\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductSchema')]
class Product
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'seller_id', description: '卖家id', type: 'int')]
    protected int $sellerId;

    #[OA\Property(property: 'shop_id', description: '店铺id', type: 'int')]
    protected int $shopId;

    #[OA\Property(property: 'category_id', description: '分类id', type: 'int')]
    protected int $categoryId;

    #[OA\Property(property: 'category_name', description: '分类名称', type: 'string')]
    protected string $categoryName;

    #[OA\Property(property: 'brand_id', description: '品牌id', type: 'int')]
    protected int $brandId;

    #[OA\Property(property: 'brand_name', description: '品牌名称', type: 'string')]
    protected string $brandName;

    #[OA\Property(property: 'freight_template_id', description: '运费模版id', type: 'int')]
    protected int $freightTemplateId;

    #[OA\Property(property: 'product_type_id', description: '商品类型id', type: 'int')]
    protected int $productTypeId;

    #[OA\Property(property: 'product_sn', description: '货号', type: 'string')]
    protected string $productSn;

    #[OA\Property(property: 'name', description: '商品名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'pic', description: '图片', type: 'string')]
    protected string $pic;

    #[OA\Property(property: 'original_price', description: '市场价', type: 'float')]
    protected float $originalPrice;

    #[OA\Property(property: 'price', description: '价格', type: 'float')]
    protected float $price;

    #[OA\Property(property: 'promotion_type', description: '促销类型：0->没有促销使用原价;1->使用促销价；2->使用会员价；3->使用阶梯价格；4->使用满减价格；5->限时购', type: 'int')]
    protected int $promotionType;

    #[OA\Property(property: 'promotion_price', description: '促销价格', type: 'float')]
    protected float $promotionPrice;

    #[OA\Property(property: 'promotion_start_time', description: '促销开始时间', type: 'string')]
    protected string $promotionStartTime;

    #[OA\Property(property: 'promotion_end_time', description: '促销结束时间', type: 'string')]
    protected string $promotionEndTime;

    #[OA\Property(property: 'promotion_per_limit', description: '活动限购数量', type: 'int')]
    protected int $promotionPerLimit;

    #[OA\Property(property: 'gift_growth', description: '赠送的成长值', type: 'int')]
    protected int $giftGrowth;

    #[OA\Property(property: 'gift_point', description: '赠送的积分', type: 'int')]
    protected int $giftPoint;

    #[OA\Property(property: 'use_point_limit', description: '限制使用的积分数', type: 'int')]
    protected int $usePointLimit;

    #[OA\Property(property: 'sale', description: '销量', type: 'int')]
    protected int $sale;

    #[OA\Property(property: 'stock', description: '库存', type: 'int')]
    protected int $stock;

    #[OA\Property(property: 'low_stock', description: '库存预警值', type: 'int')]
    protected int $lowStock;

    #[OA\Property(property: 'unit', description: '单位', type: 'string')]
    protected string $unit;

    #[OA\Property(property: 'weight', description: '商品重量，默认为克', type: 'float')]
    protected float $weight;

    #[OA\Property(property: 'preview_status', description: '是否为预告商品：0->不是；1->是', type: 'int')]
    protected int $previewStatus;

    #[OA\Property(property: 'service_ids', description: '以逗号分割的产品服务：1->无忧退货；2->快速退款；3->免费包邮', type: 'string')]
    protected string $serviceIds;

    #[OA\Property(property: 'sub_title', description: '副标题', type: 'string')]
    protected string $subTitle;

    #[OA\Property(property: 'description', description: '商品描述', type: 'string')]
    protected string $description;

    #[OA\Property(property: 'keywords', description: '关键字', type: 'string')]
    protected string $keywords;

    #[OA\Property(property: 'note', description: '备注', type: 'string')]
    protected string $note;

    #[OA\Property(property: 'album_pics', description: '画册图片，连产品图片限制为5张，以逗号分割', type: 'string')]
    protected string $albumPics;

    #[OA\Property(property: 'detail_title', description: '详情标题', type: 'string')]
    protected string $detailTitle;

    #[OA\Property(property: 'detail_desc', description: '详情描述', type: 'string')]
    protected string $detailDesc;

    #[OA\Property(property: 'detail_html', description: '产品详情网页内容', type: 'string')]
    protected string $detailHtml;

    #[OA\Property(property: 'detail_mobile_html', description: '移动端网页详情', type: 'string')]
    protected string $detailMobileHtml;

    #[OA\Property(property: 'delete_status', description: '删除状态：0->未删除；1->已删除', type: 'int')]
    protected int $deleteStatus;

    #[OA\Property(property: 'publish_status', description: '上架状态：0->下架；1->上架', type: 'int')]
    protected int $publishStatus;

    #[OA\Property(property: 'new_status', description: '新品状态:0->不是新品；1->新品', type: 'int')]
    protected int $newStatus;

    #[OA\Property(property: 'recommend_status', description: '推荐状态；0->不推荐；1->推荐', type: 'int')]
    protected int $recommendStatus;

    #[OA\Property(property: 'verify_status', description: '审核状态：0->未审核；1->审核通过', type: 'int')]
    protected int $verifyStatus;

    #[OA\Property(property: 'sort', description: '排序', type: 'int')]
    protected int $sort;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deletedAt;

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
     * 获取卖家id
     */
    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    /**
     * 设置卖家id
     */
    public function setSellerId(int $sellerId): void
    {
        $this->sellerId = $sellerId;
    }

    /**
     * 获取店铺id
     */
    public function getShopId(): int
    {
        return $this->shopId;
    }

    /**
     * 设置店铺id
     */
    public function setShopId(int $shopId): void
    {
        $this->shopId = $shopId;
    }

    /**
     * 获取分类id
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * 设置分类id
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * 获取分类名称
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * 设置分类名称
     */
    public function setCategoryName(string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    /**
     * 获取品牌id
     */
    public function getBrandId(): int
    {
        return $this->brandId;
    }

    /**
     * 设置品牌id
     */
    public function setBrandId(int $brandId): void
    {
        $this->brandId = $brandId;
    }

    /**
     * 获取品牌名称
     */
    public function getBrandName(): string
    {
        return $this->brandName;
    }

    /**
     * 设置品牌名称
     */
    public function setBrandName(string $brandName): void
    {
        $this->brandName = $brandName;
    }

    /**
     * 获取运费模版id
     */
    public function getFreightTemplateId(): int
    {
        return $this->freightTemplateId;
    }

    /**
     * 设置运费模版id
     */
    public function setFreightTemplateId(int $freightTemplateId): void
    {
        $this->freightTemplateId = $freightTemplateId;
    }

    /**
     * 获取商品类型id
     */
    public function getProductTypeId(): int
    {
        return $this->productTypeId;
    }

    /**
     * 设置商品类型id
     */
    public function setProductTypeId(int $productTypeId): void
    {
        $this->productTypeId = $productTypeId;
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
     * 获取商品名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置商品名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取图片
     */
    public function getPic(): string
    {
        return $this->pic;
    }

    /**
     * 设置图片
     */
    public function setPic(string $pic): void
    {
        $this->pic = $pic;
    }

    /**
     * 获取市场价
     */
    public function getOriginalPrice(): float
    {
        return $this->originalPrice;
    }

    /**
     * 设置市场价
     */
    public function setOriginalPrice(float $originalPrice): void
    {
        $this->originalPrice = $originalPrice;
    }

    /**
     * 获取价格
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * 设置价格
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * 获取促销类型：0->没有促销使用原价;1->使用促销价；2->使用会员价；3->使用阶梯价格；4->使用满减价格；5->限时购
     */
    public function getPromotionType(): int
    {
        return $this->promotionType;
    }

    /**
     * 设置促销类型：0->没有促销使用原价;1->使用促销价；2->使用会员价；3->使用阶梯价格；4->使用满减价格；5->限时购
     */
    public function setPromotionType(int $promotionType): void
    {
        $this->promotionType = $promotionType;
    }

    /**
     * 获取促销价格
     */
    public function getPromotionPrice(): float
    {
        return $this->promotionPrice;
    }

    /**
     * 设置促销价格
     */
    public function setPromotionPrice(float $promotionPrice): void
    {
        $this->promotionPrice = $promotionPrice;
    }

    /**
     * 获取促销开始时间
     */
    public function getPromotionStartTime(): string
    {
        return $this->promotionStartTime;
    }

    /**
     * 设置促销开始时间
     */
    public function setPromotionStartTime(string $promotionStartTime): void
    {
        $this->promotionStartTime = $promotionStartTime;
    }

    /**
     * 获取促销结束时间
     */
    public function getPromotionEndTime(): string
    {
        return $this->promotionEndTime;
    }

    /**
     * 设置促销结束时间
     */
    public function setPromotionEndTime(string $promotionEndTime): void
    {
        $this->promotionEndTime = $promotionEndTime;
    }

    /**
     * 获取活动限购数量
     */
    public function getPromotionPerLimit(): int
    {
        return $this->promotionPerLimit;
    }

    /**
     * 设置活动限购数量
     */
    public function setPromotionPerLimit(int $promotionPerLimit): void
    {
        $this->promotionPerLimit = $promotionPerLimit;
    }

    /**
     * 获取赠送的成长值
     */
    public function getGiftGrowth(): int
    {
        return $this->giftGrowth;
    }

    /**
     * 设置赠送的成长值
     */
    public function setGiftGrowth(int $giftGrowth): void
    {
        $this->giftGrowth = $giftGrowth;
    }

    /**
     * 获取赠送的积分
     */
    public function getGiftPoint(): int
    {
        return $this->giftPoint;
    }

    /**
     * 设置赠送的积分
     */
    public function setGiftPoint(int $giftPoint): void
    {
        $this->giftPoint = $giftPoint;
    }

    /**
     * 获取限制使用的积分数
     */
    public function getUsePointLimit(): int
    {
        return $this->usePointLimit;
    }

    /**
     * 设置限制使用的积分数
     */
    public function setUsePointLimit(int $usePointLimit): void
    {
        $this->usePointLimit = $usePointLimit;
    }

    /**
     * 获取销量
     */
    public function getSale(): int
    {
        return $this->sale;
    }

    /**
     * 设置销量
     */
    public function setSale(int $sale): void
    {
        $this->sale = $sale;
    }

    /**
     * 获取库存
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * 设置库存
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * 获取库存预警值
     */
    public function getLowStock(): int
    {
        return $this->lowStock;
    }

    /**
     * 设置库存预警值
     */
    public function setLowStock(int $lowStock): void
    {
        $this->lowStock = $lowStock;
    }

    /**
     * 获取单位
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * 设置单位
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * 获取商品重量，默认为克
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * 设置商品重量，默认为克
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * 获取是否为预告商品：0->不是；1->是
     */
    public function getPreviewStatus(): int
    {
        return $this->previewStatus;
    }

    /**
     * 设置是否为预告商品：0->不是；1->是
     */
    public function setPreviewStatus(int $previewStatus): void
    {
        $this->previewStatus = $previewStatus;
    }

    /**
     * 获取以逗号分割的产品服务：1->无忧退货；2->快速退款；3->免费包邮
     */
    public function getServiceIds(): string
    {
        return $this->serviceIds;
    }

    /**
     * 设置以逗号分割的产品服务：1->无忧退货；2->快速退款；3->免费包邮
     */
    public function setServiceIds(string $serviceIds): void
    {
        $this->serviceIds = $serviceIds;
    }

    /**
     * 获取副标题
     */
    public function getSubTitle(): string
    {
        return $this->subTitle;
    }

    /**
     * 设置副标题
     */
    public function setSubTitle(string $subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    /**
     * 获取商品描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置商品描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取关键字
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * 设置关键字
     */
    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * 获取备注
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * 设置备注
     */
    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    /**
     * 获取画册图片，连产品图片限制为5张，以逗号分割
     */
    public function getAlbumPics(): string
    {
        return $this->albumPics;
    }

    /**
     * 设置画册图片，连产品图片限制为5张，以逗号分割
     */
    public function setAlbumPics(string $albumPics): void
    {
        $this->albumPics = $albumPics;
    }

    /**
     * 获取详情标题
     */
    public function getDetailTitle(): string
    {
        return $this->detailTitle;
    }

    /**
     * 设置详情标题
     */
    public function setDetailTitle(string $detailTitle): void
    {
        $this->detailTitle = $detailTitle;
    }

    /**
     * 获取详情描述
     */
    public function getDetailDesc(): string
    {
        return $this->detailDesc;
    }

    /**
     * 设置详情描述
     */
    public function setDetailDesc(string $detailDesc): void
    {
        $this->detailDesc = $detailDesc;
    }

    /**
     * 获取产品详情网页内容
     */
    public function getDetailHtml(): string
    {
        return $this->detailHtml;
    }

    /**
     * 设置产品详情网页内容
     */
    public function setDetailHtml(string $detailHtml): void
    {
        $this->detailHtml = $detailHtml;
    }

    /**
     * 获取移动端网页详情
     */
    public function getDetailMobileHtml(): string
    {
        return $this->detailMobileHtml;
    }

    /**
     * 设置移动端网页详情
     */
    public function setDetailMobileHtml(string $detailMobileHtml): void
    {
        $this->detailMobileHtml = $detailMobileHtml;
    }

    /**
     * 获取删除状态：0->未删除；1->已删除
     */
    public function getDeleteStatus(): int
    {
        return $this->deleteStatus;
    }

    /**
     * 设置删除状态：0->未删除；1->已删除
     */
    public function setDeleteStatus(int $deleteStatus): void
    {
        $this->deleteStatus = $deleteStatus;
    }

    /**
     * 获取上架状态：0->下架；1->上架
     */
    public function getPublishStatus(): int
    {
        return $this->publishStatus;
    }

    /**
     * 设置上架状态：0->下架；1->上架
     */
    public function setPublishStatus(int $publishStatus): void
    {
        $this->publishStatus = $publishStatus;
    }

    /**
     * 获取新品状态:0->不是新品；1->新品
     */
    public function getNewStatus(): int
    {
        return $this->newStatus;
    }

    /**
     * 设置新品状态:0->不是新品；1->新品
     */
    public function setNewStatus(int $newStatus): void
    {
        $this->newStatus = $newStatus;
    }

    /**
     * 获取推荐状态；0->不推荐；1->推荐
     */
    public function getRecommendStatus(): int
    {
        return $this->recommendStatus;
    }

    /**
     * 设置推荐状态；0->不推荐；1->推荐
     */
    public function setRecommendStatus(int $recommendStatus): void
    {
        $this->recommendStatus = $recommendStatus;
    }

    /**
     * 获取审核状态：0->未审核；1->审核通过
     */
    public function getVerifyStatus(): int
    {
        return $this->verifyStatus;
    }

    /**
     * 设置审核状态：0->未审核；1->审核通过
     */
    public function setVerifyStatus(int $verifyStatus): void
    {
        $this->verifyStatus = $verifyStatus;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
