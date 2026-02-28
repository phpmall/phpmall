<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Requests\ShippingArea;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShippingAreaUpdateRequest',
    required: [
        self::getShippingAreaId,
        self::getShippingAreaName,
        self::getShippingId,
        self::getConfigure,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getShippingAreaId, description: '', type: 'integer'),
        new OA\Property(property: self::getShippingAreaName, description: '配送区域名称', type: 'string'),
        new OA\Property(property: self::getShippingId, description: '配送方式ID', type: 'integer'),
        new OA\Property(property: self::getConfigure, description: '配置信息', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShippingAreaUpdateRequest extends FormRequest
{
    const string getShippingAreaId = 'shippingAreaId';

    const string getShippingAreaName = 'shippingAreaName';

    const string getShippingId = 'shippingId';

    const string getConfigure = 'configure';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getShippingAreaId => 'required',
            self::getShippingAreaName => 'required',
            self::getShippingId => 'required',
            self::getConfigure => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getShippingAreaId.'.required' => '请设置',
            self::getShippingAreaName.'.required' => '请设置配送区域名称',
            self::getShippingId.'.required' => '请设置配送方式ID',
            self::getConfigure.'.required' => '请设置配置信息',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
