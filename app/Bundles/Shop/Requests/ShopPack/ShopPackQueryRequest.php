<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopPack;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopPackQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getPackId, description: '', type: 'integer'),
    ]
)]
class ShopPackQueryRequest extends FormRequest
{
    const string getPackId = 'packId';

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
