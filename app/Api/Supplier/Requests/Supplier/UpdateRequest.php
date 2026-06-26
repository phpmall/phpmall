<?php

declare(strict_types=1);

namespace App\Api\Supplier\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierSupplierUpdateRequest',
    required: [
        self::getCompanyName,
        self::getContactName,
        self::getContactPhone,
    ],
    properties: [
        new OA\Property(property: self::getCompanyName, description: '公司名称', type: 'string'),
        new OA\Property(property: self::getContactName, description: '联系人姓名', type: 'string'),
        new OA\Property(property: self::getContactPhone, description: '联系人电话', type: 'string'),
        new OA\Property(property: self::getContactEmail, description: '联系人邮箱', type: 'string', nullable: true),
        new OA\Property(property: self::getAddress, description: '公司地址', type: 'string', nullable: true),
        new OA\Property(property: self::getBusinessLicense, description: '营业执照号', type: 'string', nullable: true),
        new OA\Property(property: self::getBankName, description: '开户银行', type: 'string', nullable: true),
        new OA\Property(property: self::getBankAccount, description: '银行账号', type: 'string', nullable: true),
        new OA\Property(property: self::getTaxNo, description: '纳税人识别号', type: 'string', nullable: true),
    ]
)]
class UpdateRequest extends FormRequest
{
    const string getCompanyName = 'company_name';

    const string getContactName = 'contact_name';

    const string getContactPhone = 'contact_phone';

    const string getContactEmail = 'contact_email';

    const string getAddress = 'address';

    const string getBusinessLicense = 'business_license';

    const string getBankName = 'bank_name';

    const string getBankAccount = 'bank_account';

    const string getTaxNo = 'tax_no';

    public function rules(): array
    {
        return [
            self::getCompanyName => ['required', 'string', 'max:200'],
            self::getContactName => ['required', 'string', 'max:50'],
            self::getContactPhone => ['required', 'string', 'max:20'],
            self::getContactEmail => ['nullable', 'string', 'email', 'max:100'],
            self::getAddress => ['nullable', 'string', 'max:500'],
            self::getBusinessLicense => ['nullable', 'string', 'max:100'],
            self::getBankName => ['nullable', 'string', 'max:100'],
            self::getBankAccount => ['nullable', 'string', 'max:50'],
            self::getTaxNo => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getCompanyName.'.required' => '请填写公司名称',
            self::getCompanyName.'.max' => '公司名称不能超过200个字符',
            self::getContactName.'.required' => '请填写联系人姓名',
            self::getContactName.'.max' => '联系人姓名不能超过50个字符',
            self::getContactPhone.'.required' => '请填写联系人电话',
            self::getContactPhone.'.max' => '联系人电话不能超过20个字符',
            self::getContactEmail.'.email' => '邮箱格式不正确',
            self::getAddress.'.max' => '地址不能超过500个字符',
            self::getBusinessLicense.'.max' => '营业执照号不能超过100个字符',
            self::getBankName.'.max' => '开户银行不能超过100个字符',
            self::getBankAccount.'.max' => '银行账号不能超过50个字符',
            self::getTaxNo.'.max' => '纳税人识别号不能超过50个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
