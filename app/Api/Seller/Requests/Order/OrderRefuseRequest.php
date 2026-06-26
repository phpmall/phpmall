<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerOrderRefuseRequest',
    required: [
        self::getReason,
    ],
    properties: [
        new OA\Property(property: self::getReason, description: '拒绝原因', type: 'string'),
    ]
)]
class OrderRefuseRequest extends FormRequest
{
    const string getReason = 'reason';

    public function rules(): array
    {
        return [
            self::getReason => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            self::getReason.'.required' => '请填写拒绝原因',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
