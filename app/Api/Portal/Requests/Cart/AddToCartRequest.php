<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalAddToCartRequest',
    title: '加入购物车请求参数',
    required: ['sku_id'],
    properties: [
        new OA\Property(property: 'sku_id', description: '商品SKU ID', type: 'integer', example: 2001),
        new OA\Property(property: 'quantity', description: '加购数量（默认1）', type: 'integer', default: 1, example: 1),
    ]
)]
class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'sku_id' => ['required', 'integer', 'min:1'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
        ];
    }
}
