<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\PaymentRefund;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentRefundUpdateRequest',
    required: [
        self::getId,
        self::getRefundNo,
        self::getPaymentId,
        self::getOrderId,
        self::getOrderRefundId,
        self::getAmount,
        self::getChannel,
        self::getStatus,
        self::getRefundedAt,
        self::getChannelRefundId,
        self::getFailureReason,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRefundNo, description: '退款单号', type: 'string'),
        new OA\Property(property: self::getPaymentId, description: '原支付记录ID', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getOrderRefundId, description: '关联售后单', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '退款金额（分）', type: 'integer'),
        new OA\Property(property: self::getChannel, description: '原支付渠道：1-微信，2-支付宝，3-余额，4-银联', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '退款状态：0-待退款，1-退款中，2-成功，3-失败', type: 'integer'),
        new OA\Property(property: self::getRefundedAt, description: '退款成功时间', type: 'string'),
        new OA\Property(property: self::getChannelRefundId, description: '渠道退款单号', type: 'string'),
        new OA\Property(property: self::getFailureReason, description: '失败原因', type: 'string'),
    ]
)]
class PaymentRefundUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRefundNo = 'refundNo';

    public const string getPaymentId = 'paymentId';

    public const string getOrderId = 'orderId';

    public const string getOrderRefundId = 'orderRefundId';

    public const string getAmount = 'amount';

    public const string getChannel = 'channel';

    public const string getStatus = 'status';

    public const string getRefundedAt = 'refundedAt';

    public const string getChannelRefundId = 'channelRefundId';

    public const string getFailureReason = 'failureReason';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getRefundNo => 'required',
            self::getPaymentId => 'required',
            self::getOrderId => 'required',
            self::getOrderRefundId => 'required',
            self::getAmount => 'required',
            self::getChannel => 'required',
            self::getStatus => 'required',
            self::getRefundedAt => 'required',
            self::getChannelRefundId => 'required',
            self::getFailureReason => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getRefundNo.'.required' => '请设置退款单号',
            self::getPaymentId.'.required' => '请设置原支付记录ID',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getOrderRefundId.'.required' => '请设置关联售后单',
            self::getAmount.'.required' => '请设置退款金额（分）',
            self::getChannel.'.required' => '请设置原支付渠道：1-微信，2-支付宝，3-余额，4-银联',
            self::getStatus.'.required' => '请设置退款状态：0-待退款，1-退款中，2-成功，3-失败',
            self::getRefundedAt.'.required' => '请设置退款成功时间',
            self::getChannelRefundId.'.required' => '请设置渠道退款单号',
            self::getFailureReason.'.required' => '请设置失败原因',
        ];
    }
}
