<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductEntity')]
class ProductEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'merchant_id', description: '商户id', type: 'integer')]
    protected int $merchant_id;

    #[OA\Property(property: 'shop_id', description: '店铺id', type: 'integer')]
    protected int $shop_id;

    #[OA\Property(property: 'category_id', description: '分类id', type: 'integer')]
    protected int $category_id;

    #[OA\Property(property: 'category_name', description: '分类名称', type: 'string')]
    protected string $category_name;

    #[OA\Property(property: 'brand_id', description: '品牌id', type: 'integer')]
    protected int $brand_id;

    #[OA\Property(property: 'brand_name', description: '品牌名称', type: 'string')]
    protected string $brand_name;

    #[OA\Property(property: 'freight_template_id', description: '运费模版id', type: 'integer')]
    protected int $freight_template_id;

    #[OA\Property(property: 'product_type_id', description: '商品类型id', type: 'integer')]
    protected int $product_type_id;

    #[OA\Property(property: 'product_sn', description: '货号', type: 'string')]
    protected string $product_sn;

    #[OA\Property(property: 'name', description: '商品名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'pic', description: '图片', type: 'string')]
    protected string $pic;

    #[OA\Property(property: 'original_price', description: '市场价', type: 'float')]
    protected float $original_price;

    #[OA\Property(property: 'price', description: '价格', type: 'float')]
    protected float $price;

    #[OA\Property(property: 'promotion_type', description: '促销类型：0->没有促销使用原价;1->使用促销价；2->使用会员价；3->使用阶梯价格；4->使用满减价格；5->限时购', type: 'integer')]
    protected int $promotion_type;

    #[OA\Property(property: 'promotion_price', description: '促销价格', type: 'float')]
    protected float $promotion_price;

    #[OA\Property(property: 'promotion_start_time', description: '促销开始时间', type: 'string')]
    protected string $promotion_start_time;

    #[OA\Property(property: 'promotion_end_time', description: '促销结束时间', type: 'string')]
    protected string $promotion_end_time;

    #[OA\Property(property: 'promotion_per_limit', description: '活动限购数量', type: 'integer')]
    protected int $promotion_per_limit;

    #[OA\Property(property: 'gift_growth', description: '赠送的成长值', type: 'integer')]
    protected int $gift_growth;

    #[OA\Property(property: 'gift_point', description: '赠送的积分', type: 'integer')]
    protected int $gift_point;

    #[OA\Property(property: 'use_point_limit', description: '限制使用的积分数', type: 'integer')]
    protected int $use_point_limit;

    #[OA\Property(property: 'sale', description: '销量', type: 'integer')]
    protected int $sale;

    #[OA\Property(property: 'stock', description: '库存', type: 'integer')]
    protected int $stock;

    #[OA\Property(property: 'low_stock', description: '库存预警值', type: 'integer')]
    protected int $low_stock;

    #[OA\Property(property: 'unit', description: '单位', type: 'string')]
    protected string $unit;

    #[OA\Property(property: 'weight', description: '商品重量，默认为克', type: 'float')]
    protected float $weight;

    #[OA\Property(property: 'preview_status', description: '是否为预告商品：0->不是；1->是', type: 'integer')]
    protected int $preview_status;

    #[OA\Property(property: 'service_ids', description: '以逗号分割的产品服务：1->无忧退货；2->快速退款；3->免费包邮', type: 'string')]
    protected string $service_ids;

    #[OA\Property(property: 'sub_title', description: '副标题', type: 'string')]
    protected string $sub_title;

    #[OA\Property(property: 'description', description: '商品描述', type: 'string')]
    protected string $description;

    #[OA\Property(property: 'keywords', description: '关键字', type: 'string')]
    protected string $keywords;

    #[OA\Property(property: 'note', description: '备注', type: 'string')]
    protected string $note;

    #[OA\Property(property: 'album_pics', description: '画册图片，连产品图片限制为5张，以逗号分割', type: 'string')]
    protected string $album_pics;

    #[OA\Property(property: 'detail_title', description: '详情标题', type: 'string')]
    protected string $detail_title;

    #[OA\Property(property: 'detail_desc', description: '详情描述', type: 'string')]
    protected string $detail_desc;

    #[OA\Property(property: 'detail_html', description: '产品详情网页内容', type: 'string')]
    protected string $detail_html;

    #[OA\Property(property: 'detail_mobile_html', description: '移动端网页详情', type: 'string')]
    protected string $detail_mobile_html;

    #[OA\Property(property: 'delete_status', description: '删除状态：0->未删除；1->已删除', type: 'integer')]
    protected int $delete_status;

    #[OA\Property(property: 'publish_status', description: '上架状态：0->下架；1->上架', type: 'integer')]
    protected int $publish_status;

    #[OA\Property(property: 'new_status', description: '新品状态:0->不是新品；1->新品', type: 'integer')]
    protected int $new_status;

    #[OA\Property(property: 'recommend_status', description: '推荐状态；0->不推荐；1->推荐', type: 'integer')]
    protected int $recommend_status;

    #[OA\Property(property: 'verify_status', description: '审核状态：0->未审核；1->审核通过', type: 'integer')]
    protected int $verify_status;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deleted_at;

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
     * 获取商户id
     */
    public function getMerchantId(): int
    {
        return $this->merchant_id;
    }

    /**
     * 设置商户id
     */
    public function setMerchantId(int $merchant_id): void
    {
        $this->merchant_id = $merchant_id;
    }

    /**
     * 获取店铺id
     */
    public function getShopId(): int
    {
        return $this->shop_id;
    }

    /**
     * 设置店铺id
     */
    public function setShopId(int $shop_id): void
    {
        $this->shop_id = $shop_id;
    }

    /**
     * 获取分类id
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * 设置分类id
     */
    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }

    /**
     * 获取分类名称
     */
    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    /**
     * 设置分类名称
     */
    public function setCategoryName(string $category_name): void
    {
        $this->category_name = $category_name;
    }

    /**
     * 获取品牌id
     */
    public function getBrandId(): int
    {
        return $this->brand_id;
    }

    /**
     * 设置品牌id
     */
    public function setBrandId(int $brand_id): void
    {
        $this->brand_id = $brand_id;
    }

    /**
     * 获取品牌名称
     */
    public function getBrandName(): string
    {
        return $this->brand_name;
    }

    /**
     * 设置品牌名称
     */
    public function setBrandName(string $brand_name): void
    {
        $this->brand_name = $brand_name;
    }

    /**
     * 获取运费模版id
     */
    public function getFreightTemplateId(): int
    {
        return $this->freight_template_id;
    }

    /**
     * 设置运费模版id
     */
    public function setFreightTemplateId(int $freight_template_id): void
    {
        $this->freight_template_id = $freight_template_id;
    }

    /**
     * 获取商品类型id
     */
    public function getProductTypeId(): int
    {
        return $this->product_type_id;
    }

    /**
     * 设置商品类型id
     */
    public function setProductTypeId(int $product_type_id): void
    {
        $this->product_type_id = $product_type_id;
    }

    /**
     * 获取货号
     */
    public function getProductSn(): string
    {
        return $this->product_sn;
    }

    /**
     * 设置货号
     */
    public function setProductSn(string $product_sn): void
    {
        $this->product_sn = $product_sn;
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
        return $this->original_price;
    }

    /**
     * 设置市场价
     */
    public function setOriginalPrice(float $original_price): void
    {
        $this->original_price = $original_price;
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
        return $this->promotion_type;
    }

    /**
     * 设置促销类型：0->没有促销使用原价;1->使用促销价；2->使用会员价；3->使用阶梯价格；4->使用满减价格；5->限时购
     */
    public function setPromotionType(int $promotion_type): void
    {
        $this->promotion_type = $promotion_type;
    }

    /**
     * 获取促销价格
     */
    public function getPromotionPrice(): float
    {
        return $this->promotion_price;
    }

    /**
     * 设置促销价格
     */
    public function setPromotionPrice(float $promotion_price): void
    {
        $this->promotion_price = $promotion_price;
    }

    /**
     * 获取促销开始时间
     */
    public function getPromotionStartTime(): string
    {
        return $this->promotion_start_time;
    }

    /**
     * 设置促销开始时间
     */
    public function setPromotionStartTime(string $promotion_start_time): void
    {
        $this->promotion_start_time = $promotion_start_time;
    }

    /**
     * 获取促销结束时间
     */
    public function getPromotionEndTime(): string
    {
        return $this->promotion_end_time;
    }

    /**
     * 设置促销结束时间
     */
    public function setPromotionEndTime(string $promotion_end_time): void
    {
        $this->promotion_end_time = $promotion_end_time;
    }

    /**
     * 获取活动限购数量
     */
    public function getPromotionPerLimit(): int
    {
        return $this->promotion_per_limit;
    }

    /**
     * 设置活动限购数量
     */
    public function setPromotionPerLimit(int $promotion_per_limit): void
    {
        $this->promotion_per_limit = $promotion_per_limit;
    }

    /**
     * 获取赠送的成长值
     */
    public function getGiftGrowth(): int
    {
        return $this->gift_growth;
    }

    /**
     * 设置赠送的成长值
     */
    public function setGiftGrowth(int $gift_growth): void
    {
        $this->gift_growth = $gift_growth;
    }

    /**
     * 获取赠送的积分
     */
    public function getGiftPoint(): int
    {
        return $this->gift_point;
    }

    /**
     * 设置赠送的积分
     */
    public function setGiftPoint(int $gift_point): void
    {
        $this->gift_point = $gift_point;
    }

    /**
     * 获取限制使用的积分数
     */
    public function getUsePointLimit(): int
    {
        return $this->use_point_limit;
    }

    /**
     * 设置限制使用的积分数
     */
    public function setUsePointLimit(int $use_point_limit): void
    {
        $this->use_point_limit = $use_point_limit;
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
        return $this->low_stock;
    }

    /**
     * 设置库存预警值
     */
    public function setLowStock(int $low_stock): void
    {
        $this->low_stock = $low_stock;
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
        return $this->preview_status;
    }

    /**
     * 设置是否为预告商品：0->不是；1->是
     */
    public function setPreviewStatus(int $preview_status): void
    {
        $this->preview_status = $preview_status;
    }

    /**
     * 获取以逗号分割的产品服务：1->无忧退货；2->快速退款；3->免费包邮
     */
    public function getServiceIds(): string
    {
        return $this->service_ids;
    }

    /**
     * 设置以逗号分割的产品服务：1->无忧退货；2->快速退款；3->免费包邮
     */
    public function setServiceIds(string $service_ids): void
    {
        $this->service_ids = $service_ids;
    }

    /**
     * 获取副标题
     */
    public function getSubTitle(): string
    {
        return $this->sub_title;
    }

    /**
     * 设置副标题
     */
    public function setSubTitle(string $sub_title): void
    {
        $this->sub_title = $sub_title;
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
        return $this->album_pics;
    }

    /**
     * 设置画册图片，连产品图片限制为5张，以逗号分割
     */
    public function setAlbumPics(string $album_pics): void
    {
        $this->album_pics = $album_pics;
    }

    /**
     * 获取详情标题
     */
    public function getDetailTitle(): string
    {
        return $this->detail_title;
    }

    /**
     * 设置详情标题
     */
    public function setDetailTitle(string $detail_title): void
    {
        $this->detail_title = $detail_title;
    }

    /**
     * 获取详情描述
     */
    public function getDetailDesc(): string
    {
        return $this->detail_desc;
    }

    /**
     * 设置详情描述
     */
    public function setDetailDesc(string $detail_desc): void
    {
        $this->detail_desc = $detail_desc;
    }

    /**
     * 获取产品详情网页内容
     */
    public function getDetailHtml(): string
    {
        return $this->detail_html;
    }

    /**
     * 设置产品详情网页内容
     */
    public function setDetailHtml(string $detail_html): void
    {
        $this->detail_html = $detail_html;
    }

    /**
     * 获取移动端网页详情
     */
    public function getDetailMobileHtml(): string
    {
        return $this->detail_mobile_html;
    }

    /**
     * 设置移动端网页详情
     */
    public function setDetailMobileHtml(string $detail_mobile_html): void
    {
        $this->detail_mobile_html = $detail_mobile_html;
    }

    /**
     * 获取删除状态：0->未删除；1->已删除
     */
    public function getDeleteStatus(): int
    {
        return $this->delete_status;
    }

    /**
     * 设置删除状态：0->未删除；1->已删除
     */
    public function setDeleteStatus(int $delete_status): void
    {
        $this->delete_status = $delete_status;
    }

    /**
     * 获取上架状态：0->下架；1->上架
     */
    public function getPublishStatus(): int
    {
        return $this->publish_status;
    }

    /**
     * 设置上架状态：0->下架；1->上架
     */
    public function setPublishStatus(int $publish_status): void
    {
        $this->publish_status = $publish_status;
    }

    /**
     * 获取新品状态:0->不是新品；1->新品
     */
    public function getNewStatus(): int
    {
        return $this->new_status;
    }

    /**
     * 设置新品状态:0->不是新品；1->新品
     */
    public function setNewStatus(int $new_status): void
    {
        $this->new_status = $new_status;
    }

    /**
     * 获取推荐状态；0->不推荐；1->推荐
     */
    public function getRecommendStatus(): int
    {
        return $this->recommend_status;
    }

    /**
     * 设置推荐状态；0->不推荐；1->推荐
     */
    public function setRecommendStatus(int $recommend_status): void
    {
        $this->recommend_status = $recommend_status;
    }

    /**
     * 获取审核状态：0->未审核；1->审核通过
     */
    public function getVerifyStatus(): int
    {
        return $this->verify_status;
    }

    /**
     * 设置审核状态：0->未审核；1->审核通过
     */
    public function setVerifyStatus(int $verify_status): void
    {
        $this->verify_status = $verify_status;
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
        return $this->created_at;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deleted_at;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }
}
