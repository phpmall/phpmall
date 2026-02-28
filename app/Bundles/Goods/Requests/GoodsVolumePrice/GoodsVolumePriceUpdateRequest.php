<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsVolumePrice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsVolumePriceUpdateRequest',
    required: [
        self::getId,
        self::getPriceType,
        self::getGoodsId,
        self::getVolumeNumber,
        self::getVolumePrice,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPriceType, description: '价格类型', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getVolumeNumber, description: '数量', type: 'integer'),
        new OA\Property(property: self::getVolumePrice, description: '批发价格', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsVolumePriceUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getPriceType = 'priceType';

    const string getGoodsId = 'goodsId';

    const string getVolumeNumber = 'volumeNumber';

    const string getVolumePrice = 'volumePrice';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getPriceType => 'required',
            self::getGoodsId => 'required',
            self::getVolumeNumber => 'required',
            self::getVolumePrice => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getPriceType.'.required' => '请设置价格类型',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getVolumeNumber.'.required' => '请设置数量',
            self::getVolumePrice.'.required' => '请设置批发价格',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
