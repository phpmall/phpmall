<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopAgency;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopAgencyUpdateRequest',
    required: [
        self::getAgencyId,
        self::getAgencyName,
        self::getAgencyDesc,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getAgencyId, description: '', type: 'integer'),
        new OA\Property(property: self::getAgencyName, description: '办事处名称', type: 'string'),
        new OA\Property(property: self::getAgencyDesc, description: '办事处描述', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopAgencyUpdateRequest extends FormRequest
{
    const string getAgencyId = 'agencyId';

    const string getAgencyName = 'agencyName';

    const string getAgencyDesc = 'agencyDesc';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getAgencyId => 'required',
            self::getAgencyName => 'required',
            self::getAgencyDesc => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getAgencyId.'.required' => '请设置',
            self::getAgencyName.'.required' => '请设置办事处名称',
            self::getAgencyDesc.'.required' => '请设置办事处描述',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
