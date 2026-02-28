<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Requests\ShippingAreaRegion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShippingAreaRegionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRegionId, description: '地区ID', type: 'integer'),
    ]
)]
class ShippingAreaRegionQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getRegionId = 'regionId';

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
