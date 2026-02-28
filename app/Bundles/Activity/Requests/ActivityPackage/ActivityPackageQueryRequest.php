<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityPackage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityPackageQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '货品ID', type: 'integer'),
    ]
)]
class ActivityPackageQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getProductId = 'productId';

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
