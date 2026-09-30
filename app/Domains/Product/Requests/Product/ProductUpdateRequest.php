<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductUpdateRequest',
    required: [
        self::getId,
        self::getMerchantId,
        self::getCategoryId,
        self::getTitle,
        self::getSubtitle,
        self::getDescription,
        self::getMainImage,
        self::getImages,
        self::getStatus,
        self::getAuditStatus,
        self::getAuditRemark,
        self::getMinPrice,
        self::getMaxPrice,
        self::getCostPrice,
        self::getSalesCount,
        self::getStockType,
        self::getTotalStock,
        self::getWeight,
        self::getFreightTemplateId,
        self::getAttributes,
        self::getSeoTitle,
        self::getSeoKeywords,
        self::getSeoDescription,
        self::getIsHot,
        self::getIsNew,
        self::getIsRecommend,
        self::getSortOrder,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getMerchantId, description: '商家ID', type: 'integer'),
        new OA\Property(property: self::getCategoryId, description: '分类ID', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '商品标题', type: 'string'),
        new OA\Property(property: self::getSubtitle, description: '副标题', type: 'string'),
        new OA\Property(property: self::getDescription, description: '富文本详情', type: 'string'),
        new OA\Property(property: self::getMainImage, description: '主图', type: 'string'),
        new OA\Property(property: self::getImages, description: '相册', type: 'string'),
        new OA\Property(property: self::getStatus, description: '0=下架 1=上架', type: 'integer'),
        new OA\Property(property: self::getAuditStatus, description: '0=待审核 1=通过 2=拒绝', type: 'integer'),
        new OA\Property(property: self::getAuditRemark, description: '审核备注', type: 'string'),
        new OA\Property(property: self::getMinPrice, description: '最低售价（分）', type: 'integer'),
        new OA\Property(property: self::getMaxPrice, description: '最高售价（分）', type: 'integer'),
        new OA\Property(property: self::getCostPrice, description: '成本价（分）', type: 'integer'),
        new OA\Property(property: self::getSalesCount, description: '销量', type: 'integer'),
        new OA\Property(property: self::getStockType, description: '1=统一库存 2=SKU独立库存', type: 'integer'),
        new OA\Property(property: self::getTotalStock, description: '总库存', type: 'integer'),
        new OA\Property(property: self::getWeight, description: '重量（克）', type: 'integer'),
        new OA\Property(property: self::getFreightTemplateId, description: '运费模板ID', type: 'integer'),
        new OA\Property(property: self::getAttributes, description: '规格属性定义', type: 'string'),
        new OA\Property(property: self::getSeoTitle, description: 'SEO标题', type: 'string'),
        new OA\Property(property: self::getSeoKeywords, description: 'SEO关键词', type: 'string'),
        new OA\Property(property: self::getSeoDescription, description: 'SEO描述', type: 'string'),
        new OA\Property(property: self::getIsHot, description: '是否热销', type: 'integer'),
        new OA\Property(property: self::getIsNew, description: '是否新品', type: 'integer'),
        new OA\Property(property: self::getIsRecommend, description: '是否推荐', type: 'integer'),
        new OA\Property(property: self::getSortOrder, description: '排序权重', type: 'integer'),
    ]
)]
class ProductUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getMerchantId = 'merchantId';

    public const string getCategoryId = 'categoryId';

    public const string getTitle = 'title';

    public const string getSubtitle = 'subtitle';

    public const string getDescription = 'description';

    public const string getMainImage = 'mainImage';

    public const string getImages = 'images';

    public const string getStatus = 'status';

    public const string getAuditStatus = 'auditStatus';

    public const string getAuditRemark = 'auditRemark';

    public const string getMinPrice = 'minPrice';

    public const string getMaxPrice = 'maxPrice';

    public const string getCostPrice = 'costPrice';

    public const string getSalesCount = 'salesCount';

    public const string getStockType = 'stockType';

    public const string getTotalStock = 'totalStock';

    public const string getWeight = 'weight';

    public const string getFreightTemplateId = 'freightTemplateId';

    public const string getAttributes = 'attributes';

    public const string getSeoTitle = 'seoTitle';

    public const string getSeoKeywords = 'seoKeywords';

    public const string getSeoDescription = 'seoDescription';

    public const string getIsHot = 'isHot';

    public const string getIsNew = 'isNew';

    public const string getIsRecommend = 'isRecommend';

    public const string getSortOrder = 'sortOrder';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getMerchantId => 'required',
            self::getCategoryId => 'required',
            self::getTitle => 'required',
            self::getSubtitle => 'required',
            self::getDescription => 'required',
            self::getMainImage => 'required',
            self::getImages => 'required',
            self::getStatus => 'required',
            self::getAuditStatus => 'required',
            self::getAuditRemark => 'required',
            self::getMinPrice => 'required',
            self::getMaxPrice => 'required',
            self::getCostPrice => 'required',
            self::getSalesCount => 'required',
            self::getStockType => 'required',
            self::getTotalStock => 'required',
            self::getWeight => 'required',
            self::getFreightTemplateId => 'required',
            self::getAttributes => 'required',
            self::getSeoTitle => 'required',
            self::getSeoKeywords => 'required',
            self::getSeoDescription => 'required',
            self::getIsHot => 'required',
            self::getIsNew => 'required',
            self::getIsRecommend => 'required',
            self::getSortOrder => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getMerchantId.'.required' => '请设置商家ID',
            self::getCategoryId.'.required' => '请设置分类ID',
            self::getTitle.'.required' => '请设置商品标题',
            self::getSubtitle.'.required' => '请设置副标题',
            self::getDescription.'.required' => '请设置富文本详情',
            self::getMainImage.'.required' => '请设置主图',
            self::getImages.'.required' => '请设置相册',
            self::getStatus.'.required' => '请设置0=下架 1=上架',
            self::getAuditStatus.'.required' => '请设置0=待审核 1=通过 2=拒绝',
            self::getAuditRemark.'.required' => '请设置审核备注',
            self::getMinPrice.'.required' => '请设置最低售价（分）',
            self::getMaxPrice.'.required' => '请设置最高售价（分）',
            self::getCostPrice.'.required' => '请设置成本价（分）',
            self::getSalesCount.'.required' => '请设置销量',
            self::getStockType.'.required' => '请设置1=统一库存 2=SKU独立库存',
            self::getTotalStock.'.required' => '请设置总库存',
            self::getWeight.'.required' => '请设置重量（克）',
            self::getFreightTemplateId.'.required' => '请设置运费模板ID',
            self::getAttributes.'.required' => '请设置规格属性定义',
            self::getSeoTitle.'.required' => '请设置SEO标题',
            self::getSeoKeywords.'.required' => '请设置SEO关键词',
            self::getSeoDescription.'.required' => '请设置SEO描述',
            self::getIsHot.'.required' => '请设置是否热销',
            self::getIsNew.'.required' => '请设置是否新品',
            self::getIsRecommend.'.required' => '请设置是否推荐',
            self::getSortOrder.'.required' => '请设置排序权重',
        ];
    }
}
