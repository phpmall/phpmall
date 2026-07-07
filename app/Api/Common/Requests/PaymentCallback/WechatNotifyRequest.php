<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\PaymentCallback;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonPaymentWechatNotifyRequest',
    properties: [
        new OA\Property(property: 'out_trade_no', description: '商户订单号', type: 'string'),
        new OA\Property(property: 'transaction_id', description: '微信支付订单号', type: 'string'),
        new OA\Property(property: 'trade_state', description: '交易状态', type: 'string'),
        new OA\Property(property: 'total_fee', description: '订单金额(分)', type: 'integer'),
        new OA\Property(property: 'sign', description: '签名', type: 'string'),
        new OA\Property(property: 'openid', description: '用户标识', type: 'string', nullable: true),
        new OA\Property(property: 'time_end', description: '支付完成时间', type: 'string', nullable: true),
    ]
)]
class WechatNotifyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'out_trade_no' => ['required', 'string'],
            'transaction_id' => ['required', 'string'],
            'trade_state' => ['required', 'string'],
            'total_fee' => ['required', 'integer'],
            'sign' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'out_trade_no.required' => '商户订单号不能为空',
            'transaction_id.required' => '微信支付订单号不能为空',
            'trade_state.required' => '交易状态不能为空',
            'total_fee.required' => '订单金额不能为空',
            'sign.required' => '签名不能为空',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
