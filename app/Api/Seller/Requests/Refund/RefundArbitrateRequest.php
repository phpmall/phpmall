<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Refund;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerRefundArbitrateRequest',
    required: [
        self::getResult,
    ],
    properties: [
        new OA\Property(property: self::getResult, description: '仲裁结果:1支持买家,2支持卖家', type: 'integer'),
        new OA\Property(property: self::getRemark, description: '仲裁说明', type: 'string', nullable: true),
    ]
)]
class RefundArbitrateRequest extends FormRequest
{
    const string getResult = 'result';

    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getResult => 'required|integer|in:1,2',
            self::getRemark => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            self::getResult.'.required' => '请选择仲裁结果',
            self::getResult.'.in' => '仲裁结果值不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
