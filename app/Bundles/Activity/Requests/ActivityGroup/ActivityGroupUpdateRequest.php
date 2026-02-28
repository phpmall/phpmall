<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityGroup;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityGroupUpdateRequest',
    required: [
        self::getId,
        self::getParentId,
        self::getGoodsId,
        self::getGoodsPrice,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getGoodsPrice, description: '商品价格', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ActivityGroupUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getParentId = 'parentId';

    const string getGoodsId = 'goodsId';

    const string getGoodsPrice = 'goodsPrice';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getParentId => 'required',
            self::getGoodsId => 'required',
            self::getGoodsPrice => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getParentId.'.required' => '请设置父级ID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getGoodsPrice.'.required' => '请设置商品价格',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
