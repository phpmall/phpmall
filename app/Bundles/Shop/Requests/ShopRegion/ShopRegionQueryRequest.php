<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopRegion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopRegionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getRegionId, description: '', type: 'integer'),
        new OA\Property(property: self::getRegionType, description: '地区类型', type: 'integer'),
        new OA\Property(property: self::getAgencyId, description: '办事处ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
    ]
)]
class ShopRegionQueryRequest extends FormRequest
{
    const string getRegionId = 'regionId';

    const string getRegionType = 'regionType';

    const string getAgencyId = 'agencyId';

    const string getParentId = 'parentId';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
