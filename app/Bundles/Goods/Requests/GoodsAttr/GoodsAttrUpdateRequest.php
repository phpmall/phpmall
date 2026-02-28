<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsAttr;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsAttrUpdateRequest',
    required: [
        self::getGoodsAttrId,
        self::getGoodsId,
        self::getAttrId,
        self::getAttrValue,
        self::getAttrPrice,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getGoodsAttrId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getAttrId, description: '属性ID', type: 'integer'),
        new OA\Property(property: self::getAttrValue, description: '属性值', type: 'string'),
        new OA\Property(property: self::getAttrPrice, description: '属性价格', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsAttrUpdateRequest extends FormRequest
{
    const string getGoodsAttrId = 'goodsAttrId';

    const string getGoodsId = 'goodsId';

    const string getAttrId = 'attrId';

    const string getAttrValue = 'attrValue';

    const string getAttrPrice = 'attrPrice';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getGoodsAttrId => 'required',
            self::getGoodsId => 'required',
            self::getAttrId => 'required',
            self::getAttrValue => 'required',
            self::getAttrPrice => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getGoodsAttrId.'.required' => '请设置',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getAttrId.'.required' => '请设置属性ID',
            self::getAttrValue.'.required' => '请设置属性值',
            self::getAttrPrice.'.required' => '请设置属性价格',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
