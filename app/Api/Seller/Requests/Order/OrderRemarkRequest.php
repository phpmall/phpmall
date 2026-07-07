<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerOrderRemarkRequest',
    required: [
        self::getRemark,
    ],
    properties: [
        new OA\Property(property: self::getRemark, description: '商家备注', type: 'string'),
    ]
)]
class OrderRemarkRequest extends FormRequest
{
    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getRemark => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRemark.'.required' => '请填写备注内容',
            self::getRemark.'.max' => '备注内容不能超过500个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
