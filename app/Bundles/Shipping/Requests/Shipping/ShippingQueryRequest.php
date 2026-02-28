<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Requests\Shipping;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShippingQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getShippingId, description: '', type: 'integer'),
        new OA\Property(property: self::getEnabled, description: '是否启用', type: 'integer'),
    ]
)]
class ShippingQueryRequest extends FormRequest
{
    const string getShippingId = 'shippingId';

    const string getEnabled = 'enabled';

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
