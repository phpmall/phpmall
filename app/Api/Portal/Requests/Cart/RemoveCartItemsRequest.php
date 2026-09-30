<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalRemoveCartItemsRequest',
    title: '移除购物车商品请求参数',
    required: ['cart_ids'],
    properties: [
        new OA\Property(property: 'cart_ids', description: '购物车条目ID数组', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2]),
    ]
)]
class RemoveCartItemsRequest extends FormRequest
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
            'cart_ids' => ['required', 'array', 'min:1'],
            'cart_ids.*' => ['integer'],
        ];
    }
}
