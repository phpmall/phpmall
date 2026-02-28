<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Requests\ShippingAreaRegion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShippingAreaRegionCreateRequest',
    required: [
        self::getShippingAreaId,
        self::getRegionId,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getShippingAreaId, description: '配送区域ID', type: 'integer'),
        new OA\Property(property: self::getRegionId, description: '地区ID', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShippingAreaRegionCreateRequest extends FormRequest
{
    const string getShippingAreaId = 'shippingAreaId';

    const string getRegionId = 'regionId';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getShippingAreaId => 'required',
            self::getRegionId => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getShippingAreaId.'.required' => '请设置配送区域ID',
            self::getRegionId.'.required' => '请设置地区ID',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
