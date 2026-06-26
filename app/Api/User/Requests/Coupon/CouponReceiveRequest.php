<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CouponReceiveRequest',
    required: [
        self::getCouponId,
    ],
    properties: [
        new OA\Property(property: self::getCouponId, description: '优惠券ID', type: 'integer'),
    ]
)]
class CouponReceiveRequest extends FormRequest
{
    const string getCouponId = 'coupon_id';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getCouponId => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getCouponId.'.required' => '请选择优惠券',
        ];
    }
}
