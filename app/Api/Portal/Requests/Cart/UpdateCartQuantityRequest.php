<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalUpdateCartQuantityRequest',
    title: '更新购物车数量请求参数',
    required: ['quantity'],
    properties: [
        new OA\Property(property: 'quantity', description: '新的数量', type: 'integer', example: 2),
    ]
)]
class UpdateCartQuantityRequest extends FormRequest
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
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ];
    }
}
