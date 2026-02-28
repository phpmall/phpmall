<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityWholesale;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityWholesaleCreateRequest',
    required: [
        self::getActId,
        self::getGoodsId,
        self::getGoodsName,
        self::getRankIds,
        self::getPrices,
        self::getEnabled,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getActId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getGoodsName, description: '商品名称', type: 'string'),
        new OA\Property(property: self::getRankIds, description: '等级ID', type: 'string'),
        new OA\Property(property: self::getPrices, description: '价格', type: 'string'),
        new OA\Property(property: self::getEnabled, description: '是否启用', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ActivityWholesaleCreateRequest extends FormRequest
{
    const string getActId = 'actId';

    const string getGoodsId = 'goodsId';

    const string getGoodsName = 'goodsName';

    const string getRankIds = 'rankIds';

    const string getPrices = 'prices';

    const string getEnabled = 'enabled';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getActId => 'required',
            self::getGoodsId => 'required',
            self::getGoodsName => 'required',
            self::getRankIds => 'required',
            self::getPrices => 'required',
            self::getEnabled => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getActId.'.required' => '请设置',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getGoodsName.'.required' => '请设置商品名称',
            self::getRankIds.'.required' => '请设置等级ID',
            self::getPrices.'.required' => '请设置价格',
            self::getEnabled.'.required' => '请设置是否启用',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
