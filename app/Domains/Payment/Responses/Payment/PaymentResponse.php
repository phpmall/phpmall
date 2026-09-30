<?php

declare(strict_types=1);

namespace App\Domains\Payment\Responses\Payment;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PaymentResponse')]
class PaymentResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'paymentNo', description: '支付单号', type: 'string')]
    private string $paymentNo;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'amount', description: '支付金额（分）', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'channel', description: '支付渠道：1-微信，2-支付宝，3-余额，4-银联', type: 'integer')]
    private int $channel;

    #[OA\Property(property: 'channelAppId', description: '渠道AppID', type: 'string')]
    private string $channelAppId;

    #[OA\Property(property: 'status', description: '支付状态：0-待支付，1-支付中，2-成功，3-失败，4-关闭', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'paidAt', description: '支付成功时间', type: 'string')]
    private string $paidAt;

    #[OA\Property(property: 'transactionId', description: '第三方支付流水号', type: 'string')]
    private string $transactionId;

    #[OA\Property(property: 'failureReason', description: '失败原因', type: 'string')]
    private string $failureReason;

    #[OA\Property(property: 'clientIp', description: '支付IP', type: 'string')]
    private string $clientIp;

    #[OA\Property(property: 'expiredAt', description: '支付过期时间', type: 'string')]
    private string $expiredAt;

    #[OA\Property(property: 'notifyRaw', description: '渠道回调原始数据', type: 'string')]
    private string $notifyRaw;

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
     * 获取支付单号
     */
    public function getPaymentNo(): string
    {
        return $this->paymentNo;
    }

    /**
     * 设置支付单号
     */
    public function setPaymentNo(string $paymentNo): void
    {
        $this->paymentNo = $paymentNo;
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
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取支付金额（分）
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * 设置支付金额（分）
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取支付渠道：1-微信，2-支付宝，3-余额，4-银联
     */
    public function getChannel(): int
    {
        return $this->channel;
    }

    /**
     * 设置支付渠道：1-微信，2-支付宝，3-余额，4-银联
     */
    public function setChannel(int $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * 获取渠道AppID
     */
    public function getChannelAppId(): string
    {
        return $this->channelAppId;
    }

    /**
     * 设置渠道AppID
     */
    public function setChannelAppId(string $channelAppId): void
    {
        $this->channelAppId = $channelAppId;
    }

    /**
     * 获取支付状态：0-待支付，1-支付中，2-成功，3-失败，4-关闭
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置支付状态：0-待支付，1-支付中，2-成功，3-失败，4-关闭
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取支付成功时间
     */
    public function getPaidAt(): string
    {
        return $this->paidAt;
    }

    /**
     * 设置支付成功时间
     */
    public function setPaidAt(string $paidAt): void
    {
        $this->paidAt = $paidAt;
    }

    /**
     * 获取第三方支付流水号
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * 设置第三方支付流水号
     */
    public function setTransactionId(string $transactionId): void
    {
        $this->transactionId = $transactionId;
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
     * 获取支付IP
     */
    public function getClientIp(): string
    {
        return $this->clientIp;
    }

    /**
     * 设置支付IP
     */
    public function setClientIp(string $clientIp): void
    {
        $this->clientIp = $clientIp;
    }

    /**
     * 获取支付过期时间
     */
    public function getExpiredAt(): string
    {
        return $this->expiredAt;
    }

    /**
     * 设置支付过期时间
     */
    public function setExpiredAt(string $expiredAt): void
    {
        $this->expiredAt = $expiredAt;
    }

    /**
     * 获取渠道回调原始数据
     */
    public function getNotifyRaw(): string
    {
        return $this->notifyRaw;
    }

    /**
     * 设置渠道回调原始数据
     */
    public function setNotifyRaw(string $notifyRaw): void
    {
        $this->notifyRaw = $notifyRaw;
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
