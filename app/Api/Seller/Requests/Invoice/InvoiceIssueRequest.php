<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerInvoiceIssueRequest',
    required: [
        self::getInvoiceNo,
        self::getIssueDate,
    ],
    properties: [
        new OA\Property(property: self::getInvoiceNo, description: '发票号码', type: 'string'),
        new OA\Property(property: self::getIssueDate, description: '开具日期', type: 'string', format: 'date-time'),
        new OA\Property(property: self::getRemark, description: '备注', type: 'string', nullable: true),
    ]
)]
class InvoiceIssueRequest extends FormRequest
{
    const string getInvoiceNo = 'invoice_no';

    const string getIssueDate = 'issue_date';

    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getInvoiceNo => 'required|string|max:50',
            self::getIssueDate => 'required|string|date_format:Y-m-d H:i:s',
            self::getRemark => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            self::getInvoiceNo.'.required' => '请填写发票号码',
            self::getInvoiceNo.'.max' => '发票号码不能超过50个字符',
            self::getIssueDate.'.required' => '请选择开具日期',
            self::getIssueDate.'.date_format' => '开具日期格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
