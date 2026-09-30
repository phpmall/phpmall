<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\PaymentRefund;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentRefundQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRefundNo, description: '退款单号', type: 'string'),
        new OA\Property(property: self::getPaymentId, description: '原支付记录ID', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '0=待退款 1=退款中 2=成功 3=失败', type: 'integer'),
    ]
)]
class PaymentRefundQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRefundNo = 'refundNo';

    public const string getPaymentId = 'paymentId';

    public const string getOrderId = 'orderId';

    public const string getStatus = 'status';

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
