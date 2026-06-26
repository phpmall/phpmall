<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Commission;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommissionResponse')]
class CommissionResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '佣金记录ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'order_id', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'order_no', description: '订单编号', type: 'string')]
    private string $orderNo;

    #[OA\Property(property: 'amount', description: '佣金金额(分)', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'rate', description: '佣金比例', type: 'number', format: 'float')]
    private float $rate;

    #[OA\Property(property: 'level', description: '佣金层级:1直推,2间推', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'status', description: '状态:0待结算,1已结算,2已失效', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'settled_at', description: '结算时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $settledAt;

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

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getSettledAt(): ?string
    {
        return $this->settledAt;
    }

    public function setSettledAt(?string $settledAt): void
    {
        $this->settledAt = $settledAt;
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
