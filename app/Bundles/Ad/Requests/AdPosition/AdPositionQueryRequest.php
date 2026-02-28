<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Requests\AdPosition;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdPositionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getPositionId, description: '', type: 'integer'),
    ]
)]
class AdPositionQueryRequest extends FormRequest
{
    const string getPositionId = 'positionId';

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
