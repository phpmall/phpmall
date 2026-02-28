<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsProduct;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsProductQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getProductId, description: '', type: 'integer'),
    ]
)]
class GoodsProductQueryRequest extends FormRequest
{
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
