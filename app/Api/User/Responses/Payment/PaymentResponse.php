<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Payment;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PaymentResponse')]
class PaymentResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '支付记录ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'payment_no', description: '支付单号', type: 'string')]
    private string $paymentNo;

    #[OA\Property(property: 'order_id', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'amount', description: '支付金额(分)', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'channel', description: '支付渠道', type: 'string')]
    private string $channel;

    #[OA\Property(property: 'status', description: '支付状态:0待支付,1已支付,2已关闭', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'third_party_no', description: '第三方支付单号', type: 'string', nullable: true)]
    private ?string $thirdPartyNo;

    #[OA\Property(property: 'prepay_data', description: '预支付数据', type: 'object', nullable: true)]
    private ?array $prepayData;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPaymentNo(): string
    {
        return $this->paymentNo;
    }

    public function setPaymentNo(string $paymentNo): void
    {
        $this->paymentNo = $paymentNo;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getThirdPartyNo(): ?string
    {
        return $this->thirdPartyNo;
    }

    public function setThirdPartyNo(?string $thirdPartyNo): void
    {
        $this->thirdPartyNo = $thirdPartyNo;
    }

    public function getPrepayData(): ?array
    {
        return $this->prepayData;
    }

    public function setPrepayData(?array $prepayData): void
    {
        $this->prepayData = $prepayData;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
