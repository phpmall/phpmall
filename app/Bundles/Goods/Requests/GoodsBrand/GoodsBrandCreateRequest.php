<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsBrand;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsBrandCreateRequest',
    required: [
        self::getBrandId,
        self::getBrandName,
        self::getBrandLogo,
        self::getBrandDesc,
        self::getSiteUrl,
        self::getSortOrder,
        self::getIsShow,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getBrandId, description: '', type: 'integer'),
        new OA\Property(property: self::getBrandName, description: '品牌名称', type: 'string'),
        new OA\Property(property: self::getBrandLogo, description: '品牌Logo', type: 'string'),
        new OA\Property(property: self::getBrandDesc, description: '品牌描述', type: 'string'),
        new OA\Property(property: self::getSiteUrl, description: '品牌网址', type: 'string'),
        new OA\Property(property: self::getSortOrder, description: '排序顺序', type: 'integer'),
        new OA\Property(property: self::getIsShow, description: '是否显示', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class GoodsBrandCreateRequest extends FormRequest
{
    const string getBrandId = 'brandId';

    const string getBrandName = 'brandName';

    const string getBrandLogo = 'brandLogo';

    const string getBrandDesc = 'brandDesc';

    const string getSiteUrl = 'siteUrl';

    const string getSortOrder = 'sortOrder';

    const string getIsShow = 'isShow';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getBrandId => 'required',
            self::getBrandName => 'required',
            self::getBrandLogo => 'required',
            self::getBrandDesc => 'required',
            self::getSiteUrl => 'required',
            self::getSortOrder => 'required',
            self::getIsShow => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getBrandId.'.required' => '请设置',
            self::getBrandName.'.required' => '请设置品牌名称',
            self::getBrandLogo.'.required' => '请设置品牌Logo',
            self::getBrandDesc.'.required' => '请设置品牌描述',
            self::getSiteUrl.'.required' => '请设置品牌网址',
            self::getSortOrder.'.required' => '请设置排序顺序',
            self::getIsShow.'.required' => '请设置是否显示',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
