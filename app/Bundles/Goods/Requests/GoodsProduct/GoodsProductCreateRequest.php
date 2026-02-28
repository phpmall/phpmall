<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsProduct;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsProductCreateRequest',
    required: [
        self::getProductId,
        self::getGoodsId,
        self::getGoodsAttr,
        self::getProductSn,
        self::getProductNumber,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getProductId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getGoodsAttr, description: '商品属性', type: 'string'),
        new OA\Property(property: self::getProductSn, description: '货号', type: 'string'),
        new OA\Property(property: self::getProductNumber, description: '库存数量', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsProductCreateRequest extends FormRequest
{
    const string getProductId = 'productId';

    const string getGoodsId = 'goodsId';

    const string getGoodsAttr = 'goodsAttr';

    const string getProductSn = 'productSn';

    const string getProductNumber = 'productNumber';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getProductId => 'required',
            self::getGoodsId => 'required',
            self::getGoodsAttr => 'required',
            self::getProductSn => 'required',
            self::getProductNumber => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getProductId.'.required' => '请设置',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getGoodsAttr.'.required' => '请设置商品属性',
            self::getProductSn.'.required' => '请设置货号',
            self::getProductNumber.'.required' => '请设置库存数量',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
