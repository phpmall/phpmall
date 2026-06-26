<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Withdraw;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerWithdrawStoreRequest',
    required: [
        self::getAmount,
        self::getAccountType,
        self::getAccountInfo,
    ],
    properties: [
        new OA\Property(property: self::getAmount, description: '提现金额(分)', type: 'integer'),
        new OA\Property(property: self::getAccountType, description: '账户类型:1银行卡,2支付宝,3微信', type: 'integer'),
        new OA\Property(property: self::getAccountInfo, description: '账户信息(JSON)', type: 'object'),
        new OA\Property(property: self::getRemark, description: '备注', type: 'string', nullable: true),
    ]
)]
class WithdrawStoreRequest extends FormRequest
{
    const string getAmount = 'amount';

    const string getAccountType = 'account_type';

    const string getAccountInfo = 'account_info';

    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getAmount => ['required', 'integer', 'min:100'],
            self::getAccountType => ['required', 'integer', 'in:1,2,3'],
            self::getAccountInfo => ['required', 'array'],
            self::getRemark => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getAmount.'.required' => '请填写提现金额',
            self::getAmount.'.min' => '提现金额不能小于1元',
            self::getAccountType.'.required' => '请选择账户类型',
            self::getAccountType.'.in' => '账户类型不正确',
            self::getAccountInfo.'.required' => '请填写账户信息',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
