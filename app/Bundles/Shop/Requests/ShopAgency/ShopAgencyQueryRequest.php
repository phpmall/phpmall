<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopAgency;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopAgencyQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getAgencyId, description: '', type: 'integer'),
        new OA\Property(property: self::getAgencyName, description: '办事处名称', type: 'string'),
    ]
)]
class ShopAgencyQueryRequest extends FormRequest
{
    const string getAgencyId = 'agencyId';

    const string getAgencyName = 'agencyName';

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
