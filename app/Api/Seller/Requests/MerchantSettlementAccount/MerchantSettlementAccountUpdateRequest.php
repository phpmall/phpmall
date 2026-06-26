<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\MerchantSettlementAccount;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerMerchantSettlementAccountUpdateRequest',
    required: [
        self::getAccountType,
        self::getAccountName,
        self::getAccountNumber,
        self::getBankName,
    ],
    properties: [
        new OA\Property(property: self::getAccountType, description: '账户类型:1对公,2对私', type: 'integer'),
        new OA\Property(property: self::getAccountName, description: '账户名称', type: 'string', maxLength: 100),
        new OA\Property(property: self::getAccountNumber, description: '账号', type: 'string'),
        new OA\Property(property: self::getBankName, description: '开户银行', type: 'string', maxLength: 100),
        new OA\Property(property: self::getBankBranch, description: '开户支行', type: 'string', nullable: true),
        new OA\Property(property: self::getBankCode, description: '银行联行号', type: 'string', nullable: true),
        new OA\Property(property: self::getIdCard, description: '身份证号(对私必填)', type: 'string', nullable: true),
        new OA\Property(property: self::getPhone, description: '预留手机号', type: 'string', nullable: true),
    ]
)]
class MerchantSettlementAccountUpdateRequest extends FormRequest
{
    const string getAccountType = 'account_type';

    const string getAccountName = 'account_name';

    const string getAccountNumber = 'account_number';

    const string getBankName = 'bank_name';

    const string getBankBranch = 'bank_branch';

    const string getBankCode = 'bank_code';

    const string getIdCard = 'id_card';

    const string getPhone = 'phone';

    public function rules(): array
    {
        return [
            self::getAccountType => ['required', 'integer', 'in:1,2'],
            self::getAccountName => ['required', 'string', 'max:100'],
            self::getAccountNumber => ['required', 'string'],
            self::getBankName => ['required', 'string', 'max:100'],
            self::getBankBranch => ['nullable', 'string'],
            self::getBankCode => ['nullable', 'string'],
            self::getIdCard => ['nullable', 'string', 'size:18'],
            self::getPhone => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getAccountType.'.required' => '请选择账户类型',
            self::getAccountType.'.in' => '账户类型不正确',
            self::getAccountName.'.required' => '请填写账户名称',
            self::getAccountName.'.max' => '账户名称不能超过100个字符',
            self::getAccountNumber.'.required' => '请填写账号',
            self::getBankName.'.required' => '请填写开户银行',
            self::getBankName.'.max' => '开户银行不能超过100个字符',
            self::getIdCard.'.size' => '身份证号格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
