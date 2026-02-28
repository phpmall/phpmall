<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\Goods;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsCreateRequest',
    required: [
        self::getGoodsId,
        self::getCatId,
        self::getGoodsSn,
        self::getGoodsName,
        self::getGoodsNameStyle,
        self::getClickCount,
        self::getBrandId,
        self::getProviderName,
        self::getGoodsNumber,
        self::getGoodsWeight,
        self::getMarketPrice,
        self::getShopPrice,
        self::getPromotePrice,
        self::getPromoteStartDate,
        self::getPromoteEndDate,
        self::getWarnNumber,
        self::getKeywords,
        self::getGoodsBrief,
        self::getGoodsDesc,
        self::getGoodsThumb,
        self::getGoodsImg,
        self::getOriginalImg,
        self::getIsReal,
        self::getExtensionCode,
        self::getIsOnSale,
        self::getIsAloneSale,
        self::getIsShipping,
        self::getIntegral,
        self::getAddTime,
        self::getSortOrder,
        self::getIsDelete,
        self::getIsBest,
        self::getIsNew,
        self::getIsHot,
        self::getIsPromote,
        self::getBonusTypeId,
        self::getLastUpdate,
        self::getGoodsType,
        self::getSellerNote,
        self::getGiveIntegral,
        self::getRankIntegral,
        self::getSuppliersId,
        self::getIsCheck,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getGoodsId, description: '', type: 'integer'),
        new OA\Property(property: self::getCatId, description: '商品分类ID', type: 'integer'),
        new OA\Property(property: self::getGoodsSn, description: '商品编码', type: 'string'),
        new OA\Property(property: self::getGoodsName, description: '商品名称', type: 'string'),
        new OA\Property(property: self::getGoodsNameStyle, description: '商品名称样式', type: 'string'),
        new OA\Property(property: self::getClickCount, description: '点击次数', type: 'integer'),
        new OA\Property(property: self::getBrandId, description: '商品品牌ID', type: 'integer'),
        new OA\Property(property: self::getProviderName, description: '供应商名称', type: 'string'),
        new OA\Property(property: self::getGoodsNumber, description: '商品库存', type: 'integer'),
        new OA\Property(property: self::getGoodsWeight, description: '商品重量', type: 'string'),
        new OA\Property(property: self::getMarketPrice, description: '市场价格', type: 'string'),
        new OA\Property(property: self::getShopPrice, description: '商城价格', type: 'string'),
        new OA\Property(property: self::getPromotePrice, description: '促销价格', type: 'string'),
        new OA\Property(property: self::getPromoteStartDate, description: '促销开始时间', type: 'integer'),
        new OA\Property(property: self::getPromoteEndDate, description: '促销结束时间', type: 'integer'),
        new OA\Property(property: self::getWarnNumber, description: '库存警告数量', type: 'integer'),
        new OA\Property(property: self::getKeywords, description: '关键词', type: 'string'),
        new OA\Property(property: self::getGoodsBrief, description: '商品简介', type: 'string'),
        new OA\Property(property: self::getGoodsDesc, description: '商品描述', type: 'string'),
        new OA\Property(property: self::getGoodsThumb, description: '商品缩略图', type: 'string'),
        new OA\Property(property: self::getGoodsImg, description: '商品图片', type: 'string'),
        new OA\Property(property: self::getOriginalImg, description: '商品原图', type: 'string'),
        new OA\Property(property: self::getIsReal, description: '是否实物', type: 'integer'),
        new OA\Property(property: self::getExtensionCode, description: '扩展代码', type: 'string'),
        new OA\Property(property: self::getIsOnSale, description: '是否上架', type: 'integer'),
        new OA\Property(property: self::getIsAloneSale, description: '是否单独销售', type: 'integer'),
        new OA\Property(property: self::getIsShipping, description: '是否包邮', type: 'integer'),
        new OA\Property(property: self::getIntegral, description: '积分', type: 'integer'),
        new OA\Property(property: self::getAddTime, description: '添加时间', type: 'integer'),
        new OA\Property(property: self::getSortOrder, description: '排序', type: 'integer'),
        new OA\Property(property: self::getIsDelete, description: '是否删除', type: 'integer'),
        new OA\Property(property: self::getIsBest, description: '是否精品', type: 'integer'),
        new OA\Property(property: self::getIsNew, description: '是否新品', type: 'integer'),
        new OA\Property(property: self::getIsHot, description: '是否热卖', type: 'integer'),
        new OA\Property(property: self::getIsPromote, description: '是否促销', type: 'integer'),
        new OA\Property(property: self::getBonusTypeId, description: '红包类型ID', type: 'integer'),
        new OA\Property(property: self::getLastUpdate, description: '最后更新时间', type: 'integer'),
        new OA\Property(property: self::getGoodsType, description: '商品类型', type: 'integer'),
        new OA\Property(property: self::getSellerNote, description: '商家备注', type: 'string'),
        new OA\Property(property: self::getGiveIntegral, description: '赠送积分', type: 'integer'),
        new OA\Property(property: self::getRankIntegral, description: '等级积分', type: 'integer'),
        new OA\Property(property: self::getSuppliersId, description: '供应商ID', type: 'integer'),
        new OA\Property(property: self::getIsCheck, description: '是否审核', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsCreateRequest extends FormRequest
{
    const string getGoodsId = 'goodsId';

    const string getCatId = 'catId';

    const string getGoodsSn = 'goodsSn';

    const string getGoodsName = 'goodsName';

    const string getGoodsNameStyle = 'goodsNameStyle';

    const string getClickCount = 'clickCount';

    const string getBrandId = 'brandId';

    const string getProviderName = 'providerName';

    const string getGoodsNumber = 'goodsNumber';

    const string getGoodsWeight = 'goodsWeight';

    const string getMarketPrice = 'marketPrice';

    const string getShopPrice = 'shopPrice';

    const string getPromotePrice = 'promotePrice';

    const string getPromoteStartDate = 'promoteStartDate';

    const string getPromoteEndDate = 'promoteEndDate';

    const string getWarnNumber = 'warnNumber';

    const string getKeywords = 'keywords';

    const string getGoodsBrief = 'goodsBrief';

    const string getGoodsDesc = 'goodsDesc';

    const string getGoodsThumb = 'goodsThumb';

    const string getGoodsImg = 'goodsImg';

    const string getOriginalImg = 'originalImg';

    const string getIsReal = 'isReal';

    const string getExtensionCode = 'extensionCode';

    const string getIsOnSale = 'isOnSale';

    const string getIsAloneSale = 'isAloneSale';

    const string getIsShipping = 'isShipping';

    const string getIntegral = 'integral';

    const string getAddTime = 'addTime';

    const string getSortOrder = 'sortOrder';

    const string getIsDelete = 'isDelete';

    const string getIsBest = 'isBest';

    const string getIsNew = 'isNew';

    const string getIsHot = 'isHot';

    const string getIsPromote = 'isPromote';

    const string getBonusTypeId = 'bonusTypeId';

    const string getLastUpdate = 'lastUpdate';

    const string getGoodsType = 'goodsType';

    const string getSellerNote = 'sellerNote';

    const string getGiveIntegral = 'giveIntegral';

    const string getRankIntegral = 'rankIntegral';

    const string getSuppliersId = 'suppliersId';

    const string getIsCheck = 'isCheck';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getGoodsId => 'required',
            self::getCatId => 'required',
            self::getGoodsSn => 'required',
            self::getGoodsName => 'required',
            self::getGoodsNameStyle => 'required',
            self::getClickCount => 'required',
            self::getBrandId => 'required',
            self::getProviderName => 'required',
            self::getGoodsNumber => 'required',
            self::getGoodsWeight => 'required',
            self::getMarketPrice => 'required',
            self::getShopPrice => 'required',
            self::getPromotePrice => 'required',
            self::getPromoteStartDate => 'required',
            self::getPromoteEndDate => 'required',
            self::getWarnNumber => 'required',
            self::getKeywords => 'required',
            self::getGoodsBrief => 'required',
            self::getGoodsDesc => 'required',
            self::getGoodsThumb => 'required',
            self::getGoodsImg => 'required',
            self::getOriginalImg => 'required',
            self::getIsReal => 'required',
            self::getExtensionCode => 'required',
            self::getIsOnSale => 'required',
            self::getIsAloneSale => 'required',
            self::getIsShipping => 'required',
            self::getIntegral => 'required',
            self::getAddTime => 'required',
            self::getSortOrder => 'required',
            self::getIsDelete => 'required',
            self::getIsBest => 'required',
            self::getIsNew => 'required',
            self::getIsHot => 'required',
            self::getIsPromote => 'required',
            self::getBonusTypeId => 'required',
            self::getLastUpdate => 'required',
            self::getGoodsType => 'required',
            self::getSellerNote => 'required',
            self::getGiveIntegral => 'required',
            self::getRankIntegral => 'required',
            self::getSuppliersId => 'required',
            self::getIsCheck => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getGoodsId.'.required' => '请设置',
            self::getCatId.'.required' => '请设置商品分类ID',
            self::getGoodsSn.'.required' => '请设置商品编码',
            self::getGoodsName.'.required' => '请设置商品名称',
            self::getGoodsNameStyle.'.required' => '请设置商品名称样式',
            self::getClickCount.'.required' => '请设置点击次数',
            self::getBrandId.'.required' => '请设置商品品牌ID',
            self::getProviderName.'.required' => '请设置供应商名称',
            self::getGoodsNumber.'.required' => '请设置商品库存',
            self::getGoodsWeight.'.required' => '请设置商品重量',
            self::getMarketPrice.'.required' => '请设置市场价格',
            self::getShopPrice.'.required' => '请设置商城价格',
            self::getPromotePrice.'.required' => '请设置促销价格',
            self::getPromoteStartDate.'.required' => '请设置促销开始时间',
            self::getPromoteEndDate.'.required' => '请设置促销结束时间',
            self::getWarnNumber.'.required' => '请设置库存警告数量',
            self::getKeywords.'.required' => '请设置关键词',
            self::getGoodsBrief.'.required' => '请设置商品简介',
            self::getGoodsDesc.'.required' => '请设置商品描述',
            self::getGoodsThumb.'.required' => '请设置商品缩略图',
            self::getGoodsImg.'.required' => '请设置商品图片',
            self::getOriginalImg.'.required' => '请设置商品原图',
            self::getIsReal.'.required' => '请设置是否实物',
            self::getExtensionCode.'.required' => '请设置扩展代码',
            self::getIsOnSale.'.required' => '请设置是否上架',
            self::getIsAloneSale.'.required' => '请设置是否单独销售',
            self::getIsShipping.'.required' => '请设置是否包邮',
            self::getIntegral.'.required' => '请设置积分',
            self::getAddTime.'.required' => '请设置添加时间',
            self::getSortOrder.'.required' => '请设置排序',
            self::getIsDelete.'.required' => '请设置是否删除',
            self::getIsBest.'.required' => '请设置是否精品',
            self::getIsNew.'.required' => '请设置是否新品',
            self::getIsHot.'.required' => '请设置是否热卖',
            self::getIsPromote.'.required' => '请设置是否促销',
            self::getBonusTypeId.'.required' => '请设置红包类型ID',
            self::getLastUpdate.'.required' => '请设置最后更新时间',
            self::getGoodsType.'.required' => '请设置商品类型',
            self::getSellerNote.'.required' => '请设置商家备注',
            self::getGiveIntegral.'.required' => '请设置赠送积分',
            self::getRankIntegral.'.required' => '请设置等级积分',
            self::getSuppliersId.'.required' => '请设置供应商ID',
            self::getIsCheck.'.required' => '请设置是否审核',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
