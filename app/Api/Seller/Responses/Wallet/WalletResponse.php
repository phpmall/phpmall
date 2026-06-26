<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Wallet;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerWalletResponse')]
class WalletResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '钱包ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'merchant_id', description: '商家ID', type: 'integer')]
    private int $merchantId;

    #[OA\Property(property: 'balance', description: '可用余额(分)', type: 'integer')]
    private int $balance;

    #[OA\Property(property: 'frozen_amount', description: '冻结金额(分)', type: 'integer')]
    private int $frozenAmount;

    #[OA\Property(property: 'total_income', description: '总收入(分)', type: 'integer')]
    private int $totalIncome;

    #[OA\Property(property: 'total_expense', description: '总支出(分)', type: 'integer')]
    private int $totalExpense;

    #[OA\Property(property: 'status', description: '状态:0禁用,1正常', type: 'integer')]
    private int $status;

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

    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    public function setMerchantId(int $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function getFrozenAmount(): int
    {
        return $this->frozenAmount;
    }

    public function setFrozenAmount(int $frozenAmount): void
    {
        $this->frozenAmount = $frozenAmount;
    }

    public function getTotalIncome(): int
    {
        return $this->totalIncome;
    }

    public function setTotalIncome(int $totalIncome): void
    {
        $this->totalIncome = $totalIncome;
    }

    public function getTotalExpense(): int
    {
        return $this->totalExpense;
    }

    public function setTotalExpense(int $totalExpense): void
    {
        $this->totalExpense = $totalExpense;
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
