<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityPackage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityPackageUpdateRequest',
    required: [
        self::getId,
        self::getPackageId,
        self::getGoodsId,
        self::getProductId,
        self::getGoodsNumber,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPackageId, description: '组合ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '货品ID', type: 'integer'),
        new OA\Property(property: self::getGoodsNumber, description: '商品数量', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ActivityPackageUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getPackageId = 'packageId';

    const string getGoodsId = 'goodsId';

    const string getProductId = 'productId';

    const string getGoodsNumber = 'goodsNumber';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getPackageId => 'required',
            self::getGoodsId => 'required',
            self::getProductId => 'required',
            self::getGoodsNumber => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getPackageId.'.required' => '请设置组合ID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getProductId.'.required' => '请设置货品ID',
            self::getGoodsNumber.'.required' => '请设置商品数量',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
