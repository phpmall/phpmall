<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderBackGoods;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderBackGoodsUpdateRequest',
    required: [
        self::getRecId,
        self::getBackId,
        self::getGoodsId,
        self::getProductId,
        self::getProductSn,
        self::getGoodsName,
        self::getBrandName,
        self::getGoodsSn,
        self::getIsReal,
        self::getSendNumber,
        self::getGoodsAttr,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getRecId, description: '', type: 'integer'),
        new OA\Property(property: self::getBackId, description: '退货单ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '货品ID', type: 'integer'),
        new OA\Property(property: self::getProductSn, description: '货品编号', type: 'string'),
        new OA\Property(property: self::getGoodsName, description: '商品名称', type: 'string'),
        new OA\Property(property: self::getBrandName, description: '品牌名称', type: 'string'),
        new OA\Property(property: self::getGoodsSn, description: '商品编号', type: 'string'),
        new OA\Property(property: self::getIsReal, description: '是否实物', type: 'integer'),
        new OA\Property(property: self::getSendNumber, description: '发货数量', type: 'integer'),
        new OA\Property(property: self::getGoodsAttr, description: '商品属性', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class OrderBackGoodsUpdateRequest extends FormRequest
{
    const string getRecId = 'recId';

    const string getBackId = 'backId';

    const string getGoodsId = 'goodsId';

    const string getProductId = 'productId';

    const string getProductSn = 'productSn';

    const string getGoodsName = 'goodsName';

    const string getBrandName = 'brandName';

    const string getGoodsSn = 'goodsSn';

    const string getIsReal = 'isReal';

    const string getSendNumber = 'sendNumber';

    const string getGoodsAttr = 'goodsAttr';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getRecId => 'required',
            self::getBackId => 'required',
            self::getGoodsId => 'required',
            self::getProductId => 'required',
            self::getProductSn => 'required',
            self::getGoodsName => 'required',
            self::getBrandName => 'required',
            self::getGoodsSn => 'required',
            self::getIsReal => 'required',
            self::getSendNumber => 'required',
            self::getGoodsAttr => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRecId.'.required' => '请设置',
            self::getBackId.'.required' => '请设置退货单ID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getProductId.'.required' => '请设置货品ID',
            self::getProductSn.'.required' => '请设置货品编号',
            self::getGoodsName.'.required' => '请设置商品名称',
            self::getBrandName.'.required' => '请设置品牌名称',
            self::getGoodsSn.'.required' => '请设置商品编号',
            self::getIsReal.'.required' => '请设置是否实物',
            self::getSendNumber.'.required' => '请设置发货数量',
            self::getGoodsAttr.'.required' => '请设置商品属性',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
