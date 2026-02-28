<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserRank;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserRankUpdateRequest',
    required: [
        self::getRankId,
        self::getRankName,
        self::getMinPoints,
        self::getMaxPoints,
        self::getDiscount,
        self::getShowPrice,
        self::getSpecialRank,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getRankId, description: '', type: 'integer'),
        new OA\Property(property: self::getRankName, description: '等级名称', type: 'string'),
        new OA\Property(property: self::getMinPoints, description: '最小积分', type: 'integer'),
        new OA\Property(property: self::getMaxPoints, description: '最大积分', type: 'integer'),
        new OA\Property(property: self::getDiscount, description: '折扣', type: 'integer'),
        new OA\Property(property: self::getShowPrice, description: '是否显示价格', type: 'integer'),
        new OA\Property(property: self::getSpecialRank, description: '是否特殊等级', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserRankUpdateRequest extends FormRequest
{
    const string getRankId = 'rankId';

    const string getRankName = 'rankName';

    const string getMinPoints = 'minPoints';

    const string getMaxPoints = 'maxPoints';

    const string getDiscount = 'discount';

    const string getShowPrice = 'showPrice';

    const string getSpecialRank = 'specialRank';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getRankId => 'required',
            self::getRankName => 'required',
            self::getMinPoints => 'required',
            self::getMaxPoints => 'required',
            self::getDiscount => 'required',
            self::getShowPrice => 'required',
            self::getSpecialRank => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRankId.'.required' => '请设置',
            self::getRankName.'.required' => '请设置等级名称',
            self::getMinPoints.'.required' => '请设置最小积分',
            self::getMaxPoints.'.required' => '请设置最大积分',
            self::getDiscount.'.required' => '请设置折扣',
            self::getShowPrice.'.required' => '请设置是否显示价格',
            self::getSpecialRank.'.required' => '请设置是否特殊等级',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
