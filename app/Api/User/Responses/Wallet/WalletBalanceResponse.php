<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Wallet;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WalletBalanceResponse')]
class WalletBalanceResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'balance', description: '当前余额(分)', type: 'integer')]
    private int $balance;

    #[OA\Property(property: 'frozen_balance', description: '冻结金额(分)', type: 'integer')]
    private int $frozenBalance;

    #[OA\Property(property: 'available_balance', description: '可用余额(分)', type: 'integer')]
    private int $availableBalance;

    #[OA\Property(property: 'total_recharge', description: '累计充值(分)', type: 'integer')]
    private int $totalRecharge;

    #[OA\Property(property: 'total_consumption', description: '累计消费(分)', type: 'integer')]
    private int $totalConsumption;

    #[OA\Property(property: 'currency', description: '货币单位', type: 'string')]
    private string $currency;

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function getFrozenBalance(): int
    {
        return $this->frozenBalance;
    }

    public function setFrozenBalance(int $frozenBalance): void
    {
        $this->frozenBalance = $frozenBalance;
    }

    public function getAvailableBalance(): int
    {
        return $this->availableBalance;
    }

    public function setAvailableBalance(int $availableBalance): void
    {
        $this->availableBalance = $availableBalance;
    }

    public function getTotalRecharge(): int
    {
        return $this->totalRecharge;
    }

    public function setTotalRecharge(int $totalRecharge): void
    {
        $this->totalRecharge = $totalRecharge;
    }

    public function getTotalConsumption(): int
    {
        return $this->totalConsumption;
    }

    public function setTotalConsumption(int $totalConsumption): void
    {
        $this->totalConsumption = $totalConsumption;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}
