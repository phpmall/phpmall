<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\PaymentCallback;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonPaymentUnionpayNotifyRequest',
    properties: [
        new OA\Property(property: 'orderId', description: '商户订单号', type: 'string'),
        new OA\Property(property: 'queryId', description: '银联查询流水号', type: 'string'),
        new OA\Property(property: 'respCode', description: '响应码', type: 'string'),
        new OA\Property(property: 'txnAmt', description: '交易金额(分)', type: 'integer'),
        new OA\Property(property: 'sign', description: '签名', type: 'string'),
        new OA\Property(property: 'txnTime', description: '交易时间', type: 'string', nullable: true),
    ]
)]
class UnionpayNotifyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'orderId' => ['required', 'string'],
            'queryId' => ['required', 'string'],
            'respCode' => ['required', 'string'],
            'txnAmt' => ['required', 'integer'],
            'sign' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'orderId.required' => '商户订单号不能为空',
            'queryId.required' => '银联查询流水号不能为空',
            'respCode.required' => '响应码不能为空',
            'txnAmt.required' => '交易金额不能为空',
            'sign.required' => '签名不能为空',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
