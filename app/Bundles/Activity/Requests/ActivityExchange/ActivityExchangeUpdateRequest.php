<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityExchange;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityExchangeUpdateRequest',
    required: [
        self::getId,
        self::getGoodsId,
        self::getExchangeIntegral,
        self::getIsExchange,
        self::getIsHot,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getExchangeIntegral, description: '兑换积分', type: 'integer'),
        new OA\Property(property: self::getIsExchange, description: '是否可兑换', type: 'integer'),
        new OA\Property(property: self::getIsHot, description: '是否热门', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ActivityExchangeUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getGoodsId = 'goodsId';

    const string getExchangeIntegral = 'exchangeIntegral';

    const string getIsExchange = 'isExchange';

    const string getIsHot = 'isHot';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getGoodsId => 'required',
            self::getExchangeIntegral => 'required',
            self::getIsExchange => 'required',
            self::getIsHot => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getExchangeIntegral.'.required' => '请设置兑换积分',
            self::getIsExchange.'.required' => '请设置是否可兑换',
            self::getIsHot.'.required' => '请设置是否热门',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
