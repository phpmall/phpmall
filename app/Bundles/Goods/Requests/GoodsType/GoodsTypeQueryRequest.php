<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsType;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsTypeQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getCatId, description: '', type: 'integer'),
    ]
)]
class GoodsTypeQueryRequest extends FormRequest
{
    const string getCatId = 'catId';

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
