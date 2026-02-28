<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserCart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserCartUpdateRequest',
    required: [
        self::getRecId,
        self::getUserId,
        self::getSessionId,
        self::getGoodsId,
        self::getGoodsSn,
        self::getProductId,
        self::getGoodsName,
        self::getMarketPrice,
        self::getGoodsPrice,
        self::getGoodsNumber,
        self::getGoodsAttr,
        self::getIsReal,
        self::getExtensionCode,
        self::getParentId,
        self::getRecType,
        self::getIsGift,
        self::getIsShipping,
        self::getCanHandsel,
        self::getGoodsAttrId,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getRecId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getSessionId, description: 'SessionID', type: 'string'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getGoodsSn, description: '商品编号', type: 'string'),
        new OA\Property(property: self::getProductId, description: '货品ID', type: 'integer'),
        new OA\Property(property: self::getGoodsName, description: '商品名称', type: 'string'),
        new OA\Property(property: self::getMarketPrice, description: '市场价格', type: 'string'),
        new OA\Property(property: self::getGoodsPrice, description: '商品价格', type: 'string'),
        new OA\Property(property: self::getGoodsNumber, description: '商品数量', type: 'integer'),
        new OA\Property(property: self::getGoodsAttr, description: '商品属性', type: 'string'),
        new OA\Property(property: self::getIsReal, description: '是否实物', type: 'integer'),
        new OA\Property(property: self::getExtensionCode, description: '扩展代码', type: 'string'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getRecType, description: '记录类型', type: 'integer'),
        new OA\Property(property: self::getIsGift, description: '是否赠品', type: 'integer'),
        new OA\Property(property: self::getIsShipping, description: '是否包邮', type: 'integer'),
        new OA\Property(property: self::getCanHandsel, description: '是否可以赠送', type: 'integer'),
        new OA\Property(property: self::getGoodsAttrId, description: '商品属性ID', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserCartUpdateRequest extends FormRequest
{
    const string getRecId = 'recId';

    const string getUserId = 'userId';

    const string getSessionId = 'sessionId';

    const string getGoodsId = 'goodsId';

    const string getGoodsSn = 'goodsSn';

    const string getProductId = 'productId';

    const string getGoodsName = 'goodsName';

    const string getMarketPrice = 'marketPrice';

    const string getGoodsPrice = 'goodsPrice';

    const string getGoodsNumber = 'goodsNumber';

    const string getGoodsAttr = 'goodsAttr';

    const string getIsReal = 'isReal';

    const string getExtensionCode = 'extensionCode';

    const string getParentId = 'parentId';

    const string getRecType = 'recType';

    const string getIsGift = 'isGift';

    const string getIsShipping = 'isShipping';

    const string getCanHandsel = 'canHandsel';

    const string getGoodsAttrId = 'goodsAttrId';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getRecId => 'required',
            self::getUserId => 'required',
            self::getSessionId => 'required',
            self::getGoodsId => 'required',
            self::getGoodsSn => 'required',
            self::getProductId => 'required',
            self::getGoodsName => 'required',
            self::getMarketPrice => 'required',
            self::getGoodsPrice => 'required',
            self::getGoodsNumber => 'required',
            self::getGoodsAttr => 'required',
            self::getIsReal => 'required',
            self::getExtensionCode => 'required',
            self::getParentId => 'required',
            self::getRecType => 'required',
            self::getIsGift => 'required',
            self::getIsShipping => 'required',
            self::getCanHandsel => 'required',
            self::getGoodsAttrId => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRecId.'.required' => '请设置',
            self::getUserId.'.required' => '请设置用户ID',
            self::getSessionId.'.required' => '请设置SessionID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getGoodsSn.'.required' => '请设置商品编号',
            self::getProductId.'.required' => '请设置货品ID',
            self::getGoodsName.'.required' => '请设置商品名称',
            self::getMarketPrice.'.required' => '请设置市场价格',
            self::getGoodsPrice.'.required' => '请设置商品价格',
            self::getGoodsNumber.'.required' => '请设置商品数量',
            self::getGoodsAttr.'.required' => '请设置商品属性',
            self::getIsReal.'.required' => '请设置是否实物',
            self::getExtensionCode.'.required' => '请设置扩展代码',
            self::getParentId.'.required' => '请设置父级ID',
            self::getRecType.'.required' => '请设置记录类型',
            self::getIsGift.'.required' => '请设置是否赠品',
            self::getIsShipping.'.required' => '请设置是否包邮',
            self::getCanHandsel.'.required' => '请设置是否可以赠送',
            self::getGoodsAttrId.'.required' => '请设置商品属性ID',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
