<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopCard;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopCardQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getCardId, description: '', type: 'integer'),
    ]
)]
class ShopCardQueryRequest extends FormRequest
{
    const string getCardId = 'cardId';

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
