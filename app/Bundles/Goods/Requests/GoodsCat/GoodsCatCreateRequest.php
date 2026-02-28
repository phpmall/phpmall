<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsCat;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsCatCreateRequest',
    required: [
        self::getGoodsId,
        self::getCatId,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getCatId, description: '分类ID', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsCatCreateRequest extends FormRequest
{
    const string getGoodsId = 'goodsId';

    const string getCatId = 'catId';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getGoodsId => 'required',
            self::getCatId => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getCatId.'.required' => '请设置分类ID',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
