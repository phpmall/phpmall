<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsLinkGoods;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsLinkGoodsUpdateRequest',
    required: [
        self::getId,
        self::getGoodsId,
        self::getLinkGoodsId,
        self::getIsDouble,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getLinkGoodsId, description: '关联商品ID', type: 'integer'),
        new OA\Property(property: self::getIsDouble, description: '是否双向关联', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsLinkGoodsUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getGoodsId = 'goodsId';

    const string getLinkGoodsId = 'linkGoodsId';

    const string getIsDouble = 'isDouble';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getGoodsId => 'required',
            self::getLinkGoodsId => 'required',
            self::getIsDouble => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getLinkGoodsId.'.required' => '请设置关联商品ID',
            self::getIsDouble.'.required' => '请设置是否双向关联',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
