<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Wallet;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WalletTransactionResponse')]
class WalletTransactionResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '交易记录ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '交易类型:recharge,consumption,refund,withdraw', type: 'string')]
    private string $type;

    #[OA\Property(property: 'amount', description: '交易金额(分)', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'balance_before', description: '交易前余额(分)', type: 'integer')]
    private int $balanceBefore;

    #[OA\Property(property: 'balance_after', description: '交易后余额(分)', type: 'integer')]
    private int $balanceAfter;

    #[OA\Property(property: 'description', description: '交易描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'order_no', description: '关联订单号', type: 'string', nullable: true)]
    private ?string $orderNo;

    #[OA\Property(property: 'status', description: '状态:0处理中,1成功,2失败', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'created_at', description: '交易时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getBalanceBefore(): int
    {
        return $this->balanceBefore;
    }

    public function setBalanceBefore(int $balanceBefore): void
    {
        $this->balanceBefore = $balanceBefore;
    }

    public function getBalanceAfter(): int
    {
        return $this->balanceAfter;
    }

    public function setBalanceAfter(int $balanceAfter): void
    {
        $this->balanceAfter = $balanceAfter;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getOrderNo(): ?string
    {
        return $this->orderNo;
    }

    public function setOrderNo(?string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
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
