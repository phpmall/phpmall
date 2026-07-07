<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\PaymentCallback;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonPaymentAlipayNotifyRequest',
    properties: [
        new OA\Property(property: 'out_trade_no', description: '商户订单号', type: 'string'),
        new OA\Property(property: 'trade_no', description: '支付宝交易号', type: 'string'),
        new OA\Property(property: 'trade_status', description: '交易状态', type: 'string'),
        new OA\Property(property: 'total_amount', description: '订单金额', type: 'string'),
        new OA\Property(property: 'sign', description: '签名', type: 'string'),
        new OA\Property(property: 'seller_id', description: '卖家支付宝用户号', type: 'string', nullable: true),
        new OA\Property(property: 'buyer_id', description: '买家支付宝用户号', type: 'string', nullable: true),
        new OA\Property(property: 'gmt_payment', description: '交易付款时间', type: 'string', nullable: true),
    ]
)]
class AlipayNotifyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'out_trade_no' => ['required', 'string'],
            'trade_no' => ['required', 'string'],
            'trade_status' => ['required', 'string'],
            'total_amount' => ['required', 'string'],
            'sign' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'out_trade_no.required' => '商户订单号不能为空',
            'trade_no.required' => '支付宝交易号不能为空',
            'trade_status.required' => '交易状态不能为空',
            'total_amount.required' => '订单金额不能为空',
            'sign.required' => '签名不能为空',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
