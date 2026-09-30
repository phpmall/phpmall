<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPaymentNo, description: '支付单号', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '支付状态：0-待支付，1-支付中，2-成功，3-失败，4-关闭', type: 'integer'),
        new OA\Property(property: self::getTransactionId, description: '第三方支付流水号', type: 'string'),
    ]
)]
class PaymentQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPaymentNo = 'paymentNo';

    public const string getOrderId = 'orderId';

    public const string getStatus = 'status';

    public const string getTransactionId = 'transactionId';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
