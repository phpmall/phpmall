<?php

declare(strict_types=1);

namespace App\Domains\Product\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductEntity')]
class ProductEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getMerchantId = 'merchant_id'; // 商家ID

    public const string getCategoryId = 'category_id'; // 分类ID

    public const string getTitle = 'title'; // 商品标题

    public const string getSubtitle = 'subtitle'; // 副标题

    public const string getDescription = 'description'; // 富文本详情

    public const string getMainImage = 'main_image'; // 主图

    public const string getImages = 'images'; // 相册

    public const string getStatus = 'status'; // 状态：0-下架，1-上架

    public const string getAuditStatus = 'audit_status'; // 审核状态：0-待审核，1-通过，2-拒绝

    public const string getAuditRemark = 'audit_remark'; // 审核备注

    public const string getMinPrice = 'min_price'; // 最低售价（分）

    public const string getMaxPrice = 'max_price'; // 最高售价（分）

    public const string getCostPrice = 'cost_price'; // 成本价（分）

    public const string getSalesCount = 'sales_count'; // 销量

    public const string getStockType = 'stock_type'; // 库存类型：1-统一库存，2-规格独立库存

    public const string getTotalStock = 'total_stock'; // 总库存

    public const string getWeight = 'weight'; // 重量（克）

    public const string getFreightTemplateId = 'freight_template_id'; // 运费模板ID

    public const string getAttributes = 'attributes'; // 规格属性定义

    public const string getSeoTitle = 'seo_title'; // SEO标题

    public const string getSeoKeywords = 'seo_keywords'; // SEO关键词

    public const string getSeoDescription = 'seo_description'; // SEO描述

    public const string getIsHot = 'is_hot'; // 是否热销：0-否，1-是

    public const string getIsNew = 'is_new'; // 是否新品：0-否，1-是

    public const string getIsRecommend = 'is_recommend'; // 是否推荐：0-否，1-是

    public const string getSortOrder = 'sort_order'; // 排序权重

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    public const string getDeletedAt = 'deleted_at'; // 删除时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'merchantId', description: '商家ID', type: 'integer')]
    private int $merchantId;

    #[OA\Property(property: 'categoryId', description: '分类ID', type: 'integer')]
    private int $categoryId;

    #[OA\Property(property: 'title', description: '商品标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'subtitle', description: '副标题', type: 'string')]
    private string $subtitle;

    #[OA\Property(property: 'description', description: '富文本详情', type: 'string')]
    private string $description;

    #[OA\Property(property: 'mainImage', description: '主图', type: 'string')]
    private string $mainImage;

    #[OA\Property(property: 'images', description: '相册', type: 'string')]
    private string $images;

    #[OA\Property(property: 'status', description: '状态：0-下架，1-上架', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'auditStatus', description: '审核状态：0-待审核，1-通过，2-拒绝', type: 'integer')]
    private int $auditStatus;

    #[OA\Property(property: 'auditRemark', description: '审核备注', type: 'string')]
    private string $auditRemark;

    #[OA\Property(property: 'minPrice', description: '最低售价（分）', type: 'integer')]
    private int $minPrice;

    #[OA\Property(property: 'maxPrice', description: '最高售价（分）', type: 'integer')]
    private int $maxPrice;

    #[OA\Property(property: 'costPrice', description: '成本价（分）', type: 'integer')]
    private int $costPrice;

    #[OA\Property(property: 'salesCount', description: '销量', type: 'integer')]
    private int $salesCount;

    #[OA\Property(property: 'stockType', description: '库存类型：1-统一库存，2-规格独立库存', type: 'integer')]
    private int $stockType;

    #[OA\Property(property: 'totalStock', description: '总库存', type: 'integer')]
    private int $totalStock;

    #[OA\Property(property: 'weight', description: '重量（克）', type: 'integer')]
    private int $weight;

    #[OA\Property(property: 'freightTemplateId', description: '运费模板ID', type: 'integer')]
    private int $freightTemplateId;

    #[OA\Property(property: 'attributes', description: '规格属性定义', type: 'string')]
    private string $attributes;

    #[OA\Property(property: 'seoTitle', description: 'SEO标题', type: 'string')]
    private string $seoTitle;

    #[OA\Property(property: 'seoKeywords', description: 'SEO关键词', type: 'string')]
    private string $seoKeywords;

    #[OA\Property(property: 'seoDescription', description: 'SEO描述', type: 'string')]
    private string $seoDescription;

    #[OA\Property(property: 'isHot', description: '是否热销：0-否，1-是', type: 'integer')]
    private int $isHot;

    #[OA\Property(property: 'isNew', description: '是否新品：0-否，1-是', type: 'integer')]
    private int $isNew;

    #[OA\Property(property: 'isRecommend', description: '是否推荐：0-否，1-是', type: 'integer')]
    private int $isRecommend;

    #[OA\Property(property: 'sortOrder', description: '排序权重', type: 'integer')]
    private int $sortOrder;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '删除时间', type: 'string')]
    private string $deletedAt;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取商家ID
     */
    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    /**
     * 设置商家ID
     */
    public function setMerchantId(int $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    /**
     * 获取分类ID
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * 设置分类ID
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * 获取商品标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置商品标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取副标题
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * 设置副标题
     */
    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * 获取富文本详情
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置富文本详情
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取主图
     */
    public function getMainImage(): string
    {
        return $this->mainImage;
    }

    /**
     * 设置主图
     */
    public function setMainImage(string $mainImage): void
    {
        $this->mainImage = $mainImage;
    }

    /**
     * 获取相册
     */
    public function getImages(): string
    {
        return $this->images;
    }

    /**
     * 设置相册
     */
    public function setImages(string $images): void
    {
        $this->images = $images;
    }

    /**
     * 获取状态：0-下架，1-上架
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态：0-下架，1-上架
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取审核状态：0-待审核，1-通过，2-拒绝
     */
    public function getAuditStatus(): int
    {
        return $this->auditStatus;
    }

    /**
     * 设置审核状态：0-待审核，1-通过，2-拒绝
     */
    public function setAuditStatus(int $auditStatus): void
    {
        $this->auditStatus = $auditStatus;
    }

    /**
     * 获取审核备注
     */
    public function getAuditRemark(): string
    {
        return $this->auditRemark;
    }

    /**
     * 设置审核备注
     */
    public function setAuditRemark(string $auditRemark): void
    {
        $this->auditRemark = $auditRemark;
    }

    /**
     * 获取最低售价（分）
     */
    public function getMinPrice(): int
    {
        return $this->minPrice;
    }

    /**
     * 设置最低售价（分）
     */
    public function setMinPrice(int $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    /**
     * 获取最高售价（分）
     */
    public function getMaxPrice(): int
    {
        return $this->maxPrice;
    }

    /**
     * 设置最高售价（分）
     */
    public function setMaxPrice(int $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    /**
     * 获取成本价（分）
     */
    public function getCostPrice(): int
    {
        return $this->costPrice;
    }

    /**
     * 设置成本价（分）
     */
    public function setCostPrice(int $costPrice): void
    {
        $this->costPrice = $costPrice;
    }

    /**
     * 获取销量
     */
    public function getSalesCount(): int
    {
        return $this->salesCount;
    }

    /**
     * 设置销量
     */
    public function setSalesCount(int $salesCount): void
    {
        $this->salesCount = $salesCount;
    }

    /**
     * 获取库存类型：1-统一库存，2-规格独立库存
     */
    public function getStockType(): int
    {
        return $this->stockType;
    }

    /**
     * 设置库存类型：1-统一库存，2-规格独立库存
     */
    public function setStockType(int $stockType): void
    {
        $this->stockType = $stockType;
    }

    /**
     * 获取总库存
     */
    public function getTotalStock(): int
    {
        return $this->totalStock;
    }

    /**
     * 设置总库存
     */
    public function setTotalStock(int $totalStock): void
    {
        $this->totalStock = $totalStock;
    }

    /**
     * 获取重量（克）
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * 设置重量（克）
     */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * 获取运费模板ID
     */
    public function getFreightTemplateId(): int
    {
        return $this->freightTemplateId;
    }

    /**
     * 设置运费模板ID
     */
    public function setFreightTemplateId(int $freightTemplateId): void
    {
        $this->freightTemplateId = $freightTemplateId;
    }

    /**
     * 获取规格属性定义
     */
    public function getAttributes(): string
    {
        return $this->attributes;
    }

    /**
     * 设置规格属性定义
     */
    public function setAttributes(string $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * 获取SEO标题
     */
    public function getSeoTitle(): string
    {
        return $this->seoTitle;
    }

    /**
     * 设置SEO标题
     */
    public function setSeoTitle(string $seoTitle): void
    {
        $this->seoTitle = $seoTitle;
    }

    /**
     * 获取SEO关键词
     */
    public function getSeoKeywords(): string
    {
        return $this->seoKeywords;
    }

    /**
     * 设置SEO关键词
     */
    public function setSeoKeywords(string $seoKeywords): void
    {
        $this->seoKeywords = $seoKeywords;
    }

    /**
     * 获取SEO描述
     */
    public function getSeoDescription(): string
    {
        return $this->seoDescription;
    }

    /**
     * 设置SEO描述
     */
    public function setSeoDescription(string $seoDescription): void
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * 获取是否热销：0-否，1-是
     */
    public function getIsHot(): int
    {
        return $this->isHot;
    }

    /**
     * 设置是否热销：0-否，1-是
     */
    public function setIsHot(int $isHot): void
    {
        $this->isHot = $isHot;
    }

    /**
     * 获取是否新品：0-否，1-是
     */
    public function getIsNew(): int
    {
        return $this->isNew;
    }

    /**
     * 设置是否新品：0-否，1-是
     */
    public function setIsNew(int $isNew): void
    {
        $this->isNew = $isNew;
    }

    /**
     * 获取是否推荐：0-否，1-是
     */
    public function getIsRecommend(): int
    {
        return $this->isRecommend;
    }

    /**
     * 设置是否推荐：0-否，1-是
     */
    public function setIsRecommend(int $isRecommend): void
    {
        $this->isRecommend = $isRecommend;
    }

    /**
     * 获取排序权重
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * 设置排序权重
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 获取删除时间
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置删除时间
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
