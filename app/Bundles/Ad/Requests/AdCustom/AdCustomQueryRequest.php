<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Requests\AdCustom;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdCustomQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getAdId, description: '', type: 'integer'),
    ]
)]
class AdCustomQueryRequest extends FormRequest
{
    const string getAdId = 'adId';

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
