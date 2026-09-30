<?php

declare(strict_types=1);

namespace App\Domains\Payment\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PaymentRefundEntity')]
class PaymentRefundEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getRefundNo = 'refund_no'; // 退款单号

    public const string getPaymentId = 'payment_id'; // 原支付记录ID

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getOrderRefundId = 'order_refund_id'; // 关联售后单

    public const string getAmount = 'amount'; // 退款金额（分）

    public const string getChannel = 'channel'; // 原支付渠道：1-微信，2-支付宝，3-余额，4-银联

    public const string getStatus = 'status'; // 退款状态：0-待退款，1-退款中，2-成功，3-失败

    public const string getRefundedAt = 'refunded_at'; // 退款成功时间

    public const string getChannelRefundId = 'channel_refund_id'; // 渠道退款单号

    public const string getFailureReason = 'failure_reason'; // 失败原因

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'refundNo', description: '退款单号', type: 'string')]
    private string $refundNo;

    #[OA\Property(property: 'paymentId', description: '原支付记录ID', type: 'integer')]
    private int $paymentId;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'orderRefundId', description: '关联售后单', type: 'integer')]
    private int $orderRefundId;

    #[OA\Property(property: 'amount', description: '退款金额（分）', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'channel', description: '原支付渠道：1-微信，2-支付宝，3-余额，4-银联', type: 'integer')]
    private int $channel;

    #[OA\Property(property: 'status', description: '退款状态：0-待退款，1-退款中，2-成功，3-失败', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'refundedAt', description: '退款成功时间', type: 'string')]
    private string $refundedAt;

    #[OA\Property(property: 'channelRefundId', description: '渠道退款单号', type: 'string')]
    private string $channelRefundId;

    #[OA\Property(property: 'failureReason', description: '失败原因', type: 'string')]
    private string $failureReason;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取退款单号
     */
    public function getRefundNo(): string
    {
        return $this->refundNo;
    }

    /**
     * 设置退款单号
     */
    public function setRefundNo(string $refundNo): void
    {
        $this->refundNo = $refundNo;
    }

    /**
     * 获取原支付记录ID
     */
    public function getPaymentId(): int
    {
        return $this->paymentId;
    }

    /**
     * 设置原支付记录ID
     */
    public function setPaymentId(int $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    /**
     * 获取订单ID
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置订单ID
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * 获取关联售后单
     */
    public function getOrderRefundId(): int
    {
        return $this->orderRefundId;
    }

    /**
     * 设置关联售后单
     */
    public function setOrderRefundId(int $orderRefundId): void
    {
        $this->orderRefundId = $orderRefundId;
    }

    /**
     * 获取退款金额（分）
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * 设置退款金额（分）
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取原支付渠道：1-微信，2-支付宝，3-余额，4-银联
     */
    public function getChannel(): int
    {
        return $this->channel;
    }

    /**
     * 设置原支付渠道：1-微信，2-支付宝，3-余额，4-银联
     */
    public function setChannel(int $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * 获取退款状态：0-待退款，1-退款中，2-成功，3-失败
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置退款状态：0-待退款，1-退款中，2-成功，3-失败
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取退款成功时间
     */
    public function getRefundedAt(): string
    {
        return $this->refundedAt;
    }

    /**
     * 设置退款成功时间
     */
    public function setRefundedAt(string $refundedAt): void
    {
        $this->refundedAt = $refundedAt;
    }

    /**
     * 获取渠道退款单号
     */
    public function getChannelRefundId(): string
    {
        return $this->channelRefundId;
    }

    /**
     * 设置渠道退款单号
     */
    public function setChannelRefundId(string $channelRefundId): void
    {
        $this->channelRefundId = $channelRefundId;
    }

    /**
     * 获取失败原因
     */
    public function getFailureReason(): string
    {
        return $this->failureReason;
    }

    /**
     * 设置失败原因
     */
    public function setFailureReason(string $failureReason): void
    {
        $this->failureReason = $failureReason;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
