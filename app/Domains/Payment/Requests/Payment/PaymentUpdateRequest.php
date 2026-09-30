<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentUpdateRequest',
    required: [
        self::getId,
        self::getPaymentNo,
        self::getOrderId,
        self::getUserId,
        self::getAmount,
        self::getChannel,
        self::getChannelAppId,
        self::getStatus,
        self::getPaidAt,
        self::getTransactionId,
        self::getFailureReason,
        self::getClientIp,
        self::getExpiredAt,
        self::getNotifyRaw,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPaymentNo, description: '支付单号', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '支付金额（分）', type: 'integer'),
        new OA\Property(property: self::getChannel, description: '支付渠道：1-微信，2-支付宝，3-余额，4-银联', type: 'integer'),
        new OA\Property(property: self::getChannelAppId, description: '渠道AppID', type: 'string'),
        new OA\Property(property: self::getStatus, description: '支付状态：0-待支付，1-支付中，2-成功，3-失败，4-关闭', type: 'integer'),
        new OA\Property(property: self::getPaidAt, description: '支付成功时间', type: 'string'),
        new OA\Property(property: self::getTransactionId, description: '第三方支付流水号', type: 'string'),
        new OA\Property(property: self::getFailureReason, description: '失败原因', type: 'string'),
        new OA\Property(property: self::getClientIp, description: '支付IP', type: 'string'),
        new OA\Property(property: self::getExpiredAt, description: '支付过期时间', type: 'string'),
        new OA\Property(property: self::getNotifyRaw, description: '渠道回调原始数据', type: 'string'),
    ]
)]
class PaymentUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPaymentNo = 'paymentNo';

    public const string getOrderId = 'orderId';

    public const string getUserId = 'userId';

    public const string getAmount = 'amount';

    public const string getChannel = 'channel';

    public const string getChannelAppId = 'channelAppId';

    public const string getStatus = 'status';

    public const string getPaidAt = 'paidAt';

    public const string getTransactionId = 'transactionId';

    public const string getFailureReason = 'failureReason';

    public const string getClientIp = 'clientIp';

    public const string getExpiredAt = 'expiredAt';

    public const string getNotifyRaw = 'notifyRaw';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getPaymentNo => 'required',
            self::getOrderId => 'required',
            self::getUserId => 'required',
            self::getAmount => 'required',
            self::getChannel => 'required',
            self::getChannelAppId => 'required',
            self::getStatus => 'required',
            self::getPaidAt => 'required',
            self::getTransactionId => 'required',
            self::getFailureReason => 'required',
            self::getClientIp => 'required',
            self::getExpiredAt => 'required',
            self::getNotifyRaw => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getPaymentNo.'.required' => '请设置支付单号',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getAmount.'.required' => '请设置支付金额（分）',
            self::getChannel.'.required' => '请设置支付渠道：1-微信，2-支付宝，3-余额，4-银联',
            self::getChannelAppId.'.required' => '请设置渠道AppID',
            self::getStatus.'.required' => '请设置支付状态：0-待支付，1-支付中，2-成功，3-失败，4-关闭',
            self::getPaidAt.'.required' => '请设置支付成功时间',
            self::getTransactionId.'.required' => '请设置第三方支付流水号',
            self::getFailureReason.'.required' => '请设置失败原因',
            self::getClientIp.'.required' => '请设置支付IP',
            self::getExpiredAt.'.required' => '请设置支付过期时间',
            self::getNotifyRaw.'.required' => '请设置渠道回调原始数据',
        ];
    }
}
