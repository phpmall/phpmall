<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentStoreRequest',
    required: [
        self::getOrderId,
        self::getAmount,
        self::getChannel,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '支付金额(分)', type: 'integer'),
        new OA\Property(property: self::getChannel, description: '支付渠道:wechat,alipay,unionpay', type: 'string'),
        new OA\Property(property: self::getDescription, description: '支付描述', type: 'string', nullable: true),
    ]
)]
class StoreRequest extends FormRequest
{
    const string getOrderId = 'order_id';

    const string getAmount = 'amount';

    const string getChannel = 'channel';

    const string getDescription = 'description';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getOrderId => ['required', 'integer', 'min:1'],
            self::getAmount => ['required', 'integer', 'min:1'],
            self::getChannel => ['required', 'string', 'in:wechat,alipay,unionpay'],
            self::getDescription => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请选择订单',
            self::getAmount.'.required' => '请输入支付金额',
            self::getChannel.'.required' => '请选择支付渠道',
            self::getChannel.'.in' => '支付渠道不合法',
        ];
    }
}
