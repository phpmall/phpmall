<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopRegion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopRegionUpdateRequest',
    required: [
        self::getRegionId,
        self::getRegionType,
        self::getAgencyId,
        self::getParentId,
        self::getRegionName,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getRegionId, description: '', type: 'integer'),
        new OA\Property(property: self::getRegionType, description: '地区类型', type: 'integer'),
        new OA\Property(property: self::getAgencyId, description: '办事处ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getRegionName, description: '地区名称', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopRegionUpdateRequest extends FormRequest
{
    const string getRegionId = 'regionId';

    const string getRegionType = 'regionType';

    const string getAgencyId = 'agencyId';

    const string getParentId = 'parentId';

    const string getRegionName = 'regionName';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getRegionId => 'required',
            self::getRegionType => 'required',
            self::getAgencyId => 'required',
            self::getParentId => 'required',
            self::getRegionName => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRegionId.'.required' => '请设置',
            self::getRegionType.'.required' => '请设置地区类型',
            self::getAgencyId.'.required' => '请设置办事处ID',
            self::getParentId.'.required' => '请设置父级ID',
            self::getRegionName.'.required' => '请设置地区名称',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
