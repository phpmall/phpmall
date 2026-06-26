<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'InvoiceStoreRequest',
    required: [
        self::getType,
        self::getTitle,
        self::getTaxNumber,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '发票类型:personal,company', type: 'string'),
        new OA\Property(property: self::getTitle, description: '发票抬头', type: 'string'),
        new OA\Property(property: self::getTaxNumber, description: '纳税人识别号', type: 'string'),
        new OA\Property(property: self::getEmail, description: '接收邮箱', type: 'string', nullable: true),
        new OA\Property(property: self::getPhone, description: '联系电话', type: 'string', nullable: true),
        new OA\Property(property: self::getAddress, description: '注册地址', type: 'string', nullable: true),
        new OA\Property(property: self::getBankName, description: '开户银行', type: 'string', nullable: true),
        new OA\Property(property: self::getBankAccount, description: '银行账号', type: 'string', nullable: true),
        new OA\Property(
            property: self::getOrderIds,
            description: '订单ID列表',
            type: 'array',
            items: new OA\Items(type: 'integer'),
            nullable: true
        ),
    ]
)]
class InvoiceStoreRequest extends FormRequest
{
    const string getType = 'type';

    const string getTitle = 'title';

    const string getTaxNumber = 'tax_number';

    const string getEmail = 'email';

    const string getPhone = 'phone';

    const string getAddress = 'address';

    const string getBankName = 'bank_name';

    const string getBankAccount = 'bank_account';

    const string getOrderIds = 'order_ids';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getType => ['required', 'string', 'in:personal,company'],
            self::getTitle => ['required', 'string', 'max:200'],
            self::getTaxNumber => ['required', 'string', 'regex:/^[A-Z0-9]{15,20}$/'],
            self::getEmail => ['nullable', 'email', 'max:100'],
            self::getPhone => ['nullable', 'string', 'regex:/^1[3-9]\d{9}$/'],
            self::getAddress => ['nullable', 'string', 'max:255'],
            self::getBankName => ['nullable', 'string', 'max:100'],
            self::getBankAccount => ['nullable', 'string', 'max:50'],
            self::getOrderIds => ['nullable', 'array'],
            self::getOrderIds.'.*' => ['integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请选择发票类型',
            self::getTitle.'.required' => '请填写发票抬头',
            self::getTaxNumber.'.required' => '请填写纳税人识别号',
            self::getTaxNumber.'.regex' => '纳税人识别号格式不正确',
        ];
    }
}
