<?php

declare(strict_types=1);

namespace App\Api\Supplier\Requests\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierPurchaseOrderConfirmRequest',
    properties: [
        new OA\Property(property: self::getRemark, description: '确认备注', type: 'string', nullable: true),
    ]
)]
class ConfirmRequest extends FormRequest
{
    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getRemark => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getRemark.'.max' => '备注不能超过500个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
