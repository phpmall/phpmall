<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Wallet;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerWalletBalanceResponse')]
class WalletBalanceResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'balance', description: '可用余额(分)', type: 'integer')]
    private int $balance;

    #[OA\Property(property: 'frozen_amount', description: '冻结金额(分)', type: 'integer')]
    private int $frozenAmount;

    #[OA\Property(property: 'available_balance', description: '可提现余额(分)', type: 'integer')]
    private int $availableBalance;

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

    public function getAvailableBalance(): int
    {
        return $this->availableBalance;
    }

    public function setAvailableBalance(int $availableBalance): void
    {
        $this->availableBalance = $availableBalance;
    }
}
