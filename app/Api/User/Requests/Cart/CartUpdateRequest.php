<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CartUpdateRequest',
    required: [
        self::getQuantity,
    ],
    properties: [
        new OA\Property(property: self::getQuantity, description: '购买数量', type: 'integer', minimum: 1),
    ]
)]
class CartUpdateRequest extends FormRequest
{
    const string getQuantity = 'quantity';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getQuantity => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getQuantity.'.required' => '请输入购买数量',
        ];
    }
}
