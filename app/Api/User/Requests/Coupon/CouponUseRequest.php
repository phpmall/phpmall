<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CouponUseRequest',
    required: [
        self::getOrderAmount,
    ],
    properties: [
        new OA\Property(property: self::getOrderAmount, description: '订单金额(分)', type: 'integer', minimum: 0),
    ]
)]
class CouponUseRequest extends FormRequest
{
    const string getOrderAmount = 'order_amount';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getOrderAmount => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderAmount.'.required' => '请输入订单金额',
        ];
    }
}
