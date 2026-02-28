<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityBonus;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityBonusQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getTypeId, description: '', type: 'integer'),
    ]
)]
class ActivityBonusQueryRequest extends FormRequest
{
    const string getTypeId = 'typeId';

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
