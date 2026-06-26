<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Wallet;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerWalletTransactionResponse')]
class WalletTransactionResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '交易记录ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'wallet_id', description: '钱包ID', type: 'integer')]
    private int $walletId;

    #[OA\Property(property: 'type', description: '交易类型:1收入,2支出,3冻结,4解冻', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'amount', description: '交易金额(分)', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'balance_before', description: '交易前余额(分)', type: 'integer')]
    private int $balanceBefore;

    #[OA\Property(property: 'balance_after', description: '交易后余额(分)', type: 'integer')]
    private int $balanceAfter;

    #[OA\Property(property: 'reference_type', description: '关联类型', type: 'string', nullable: true)]
    private ?string $referenceType;

    #[OA\Property(property: 'reference_id', description: '关联ID', type: 'integer', nullable: true)]
    private ?int $referenceId;

    #[OA\Property(property: 'remark', description: '备注', type: 'string', nullable: true)]
    private ?string $remark;

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

    public function getWalletId(): int
    {
        return $this->walletId;
    }

    public function setWalletId(int $walletId): void
    {
        $this->walletId = $walletId;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
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

    public function getReferenceType(): ?string
    {
        return $this->referenceType;
    }

    public function setReferenceType(?string $referenceType): void
    {
        $this->referenceType = $referenceType;
    }

    public function getReferenceId(): ?int
    {
        return $this->referenceId;
    }

    public function setReferenceId(?int $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
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
