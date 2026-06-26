<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Refund;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerRefundAuditRequest',
    required: [
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getStatus, description: '审核状态:1通过,2拒绝', type: 'integer'),
        new OA\Property(property: self::getRemark, description: '审核备注', type: 'string', nullable: true),
    ]
)]
class RefundAuditRequest extends FormRequest
{
    const string getStatus = 'status';

    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getStatus => 'required|integer|in:1,2',
            self::getRemark => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            self::getStatus.'.required' => '请选择审核结果',
            self::getStatus.'.in' => '审核状态值不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
