<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsMemberPrice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsMemberPriceCreateRequest',
    required: [
        self::getPriceId,
        self::getGoodsId,
        self::getUserRank,
        self::getUserPrice,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getPriceId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getUserRank, description: '用户等级', type: 'integer'),
        new OA\Property(property: self::getUserPrice, description: '会员价格', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsMemberPriceCreateRequest extends FormRequest
{
    const string getPriceId = 'priceId';

    const string getGoodsId = 'goodsId';

    const string getUserRank = 'userRank';

    const string getUserPrice = 'userPrice';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getPriceId => 'required',
            self::getGoodsId => 'required',
            self::getUserRank => 'required',
            self::getUserPrice => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getPriceId.'.required' => '请设置',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getUserRank.'.required' => '请设置用户等级',
            self::getUserPrice.'.required' => '请设置会员价格',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
