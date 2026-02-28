<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderGoods;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderGoodsUpdateRequest',
    required: [
        self::getRecId,
        self::getOrderId,
        self::getGoodsId,
        self::getGoodsName,
        self::getGoodsSn,
        self::getProductId,
        self::getGoodsNumber,
        self::getMarketPrice,
        self::getGoodsPrice,
        self::getGoodsAttr,
        self::getSendNumber,
        self::getIsReal,
        self::getExtensionCode,
        self::getParentId,
        self::getIsGift,
        self::getGoodsAttrId,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getRecId, description: '', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getGoodsName, description: '商品名称', type: 'string'),
        new OA\Property(property: self::getGoodsSn, description: '商品编号', type: 'string'),
        new OA\Property(property: self::getProductId, description: '货品ID', type: 'integer'),
        new OA\Property(property: self::getGoodsNumber, description: '商品数量', type: 'integer'),
        new OA\Property(property: self::getMarketPrice, description: '市场价格', type: 'string'),
        new OA\Property(property: self::getGoodsPrice, description: '商品价格', type: 'string'),
        new OA\Property(property: self::getGoodsAttr, description: '商品属性', type: 'string'),
        new OA\Property(property: self::getSendNumber, description: '发货数量', type: 'integer'),
        new OA\Property(property: self::getIsReal, description: '是否实物', type: 'integer'),
        new OA\Property(property: self::getExtensionCode, description: '扩展代码', type: 'string'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getIsGift, description: '是否赠品', type: 'integer'),
        new OA\Property(property: self::getGoodsAttrId, description: '商品属性ID', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class OrderGoodsUpdateRequest extends FormRequest
{
    const string getRecId = 'recId';

    const string getOrderId = 'orderId';

    const string getGoodsId = 'goodsId';

    const string getGoodsName = 'goodsName';

    const string getGoodsSn = 'goodsSn';

    const string getProductId = 'productId';

    const string getGoodsNumber = 'goodsNumber';

    const string getMarketPrice = 'marketPrice';

    const string getGoodsPrice = 'goodsPrice';

    const string getGoodsAttr = 'goodsAttr';

    const string getSendNumber = 'sendNumber';

    const string getIsReal = 'isReal';

    const string getExtensionCode = 'extensionCode';

    const string getParentId = 'parentId';

    const string getIsGift = 'isGift';

    const string getGoodsAttrId = 'goodsAttrId';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getRecId => 'required',
            self::getOrderId => 'required',
            self::getGoodsId => 'required',
            self::getGoodsName => 'required',
            self::getGoodsSn => 'required',
            self::getProductId => 'required',
            self::getGoodsNumber => 'required',
            self::getMarketPrice => 'required',
            self::getGoodsPrice => 'required',
            self::getGoodsAttr => 'required',
            self::getSendNumber => 'required',
            self::getIsReal => 'required',
            self::getExtensionCode => 'required',
            self::getParentId => 'required',
            self::getIsGift => 'required',
            self::getGoodsAttrId => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRecId.'.required' => '请设置',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getGoodsName.'.required' => '请设置商品名称',
            self::getGoodsSn.'.required' => '请设置商品编号',
            self::getProductId.'.required' => '请设置货品ID',
            self::getGoodsNumber.'.required' => '请设置商品数量',
            self::getMarketPrice.'.required' => '请设置市场价格',
            self::getGoodsPrice.'.required' => '请设置商品价格',
            self::getGoodsAttr.'.required' => '请设置商品属性',
            self::getSendNumber.'.required' => '请设置发货数量',
            self::getIsReal.'.required' => '请设置是否实物',
            self::getExtensionCode.'.required' => '请设置扩展代码',
            self::getParentId.'.required' => '请设置父级ID',
            self::getIsGift.'.required' => '请设置是否赠品',
            self::getGoodsAttrId.'.required' => '请设置商品属性ID',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
