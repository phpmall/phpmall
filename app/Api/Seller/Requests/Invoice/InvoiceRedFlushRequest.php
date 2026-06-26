<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerInvoiceRedFlushRequest',
    required: [
        self::getRedInvoiceNo,
        self::getReason,
    ],
    properties: [
        new OA\Property(property: self::getRedInvoiceNo, description: '红字发票号码', type: 'string'),
        new OA\Property(property: self::getReason, description: '红冲原因', type: 'string'),
        new OA\Property(property: self::getRemark, description: '备注', type: 'string', nullable: true),
    ]
)]
class InvoiceRedFlushRequest extends FormRequest
{
    const string getRedInvoiceNo = 'red_invoice_no';

    const string getReason = 'reason';

    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getRedInvoiceNo => 'required|string|max:50',
            self::getReason => 'required|string|max:500',
            self::getRemark => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRedInvoiceNo.'.required' => '请填写红字发票号码',
            self::getRedInvoiceNo.'.max' => '红字发票号码不能超过50个字符',
            self::getReason.'.required' => '请填写红冲原因',
            self::getReason.'.max' => '红冲原因不能超过500个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
