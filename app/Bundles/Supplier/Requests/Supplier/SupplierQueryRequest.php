<?php

declare(strict_types=1);

namespace App\Bundles\Supplier\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getSuppliersId, description: '', type: 'integer'),
    ]
)]
class SupplierQueryRequest extends FormRequest
{
    const string getSuppliersId = 'suppliersId';

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
