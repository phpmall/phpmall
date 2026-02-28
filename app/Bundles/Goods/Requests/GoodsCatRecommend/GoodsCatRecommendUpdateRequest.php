<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsCatRecommend;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsCatRecommendUpdateRequest',
    required: [
        self::getId,
        self::getCatId,
        self::getRecommendType,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCatId, description: '分类ID', type: 'integer'),
        new OA\Property(property: self::getRecommendType, description: '推荐类型', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsCatRecommendUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getCatId = 'catId';

    const string getRecommendType = 'recommendType';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getCatId => 'required',
            self::getRecommendType => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getCatId.'.required' => '请设置分类ID',
            self::getRecommendType.'.required' => '请设置推荐类型',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
