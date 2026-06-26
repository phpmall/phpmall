<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Commission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommissionWithdrawRequest',
    required: [
        self::getAmount,
        self::getMethod,
        self::getAccount,
    ],
    properties: [
        new OA\Property(property: self::getAmount, description: '提现金额(分)', type: 'integer', minimum: 1),
        new OA\Property(property: self::getMethod, description: '提现方式:alipay,wechat,bank', type: 'string'),
        new OA\Property(property: self::getAccount, description: '提现账号', type: 'string'),
        new OA\Property(property: self::getRealName, description: '真实姓名', type: 'string', nullable: true),
    ]
)]
class CommissionWithdrawRequest extends FormRequest
{
    const string getAmount = 'amount';

    const string getMethod = 'method';

    const string getAccount = 'account';

    const string getRealName = 'real_name';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getAmount => ['required', 'integer', 'min:100'],
            self::getMethod => ['required', 'string', 'in:alipay,wechat,bank'],
            self::getAccount => ['required', 'string', 'max:100'],
            self::getRealName => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getAmount.'.required' => '请输入提现金额',
            self::getMethod.'.required' => '请选择提现方式',
            self::getAccount.'.required' => '请输入提现账号',
        ];
    }
}
