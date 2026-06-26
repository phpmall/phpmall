<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Commission;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommissionStatsResponse')]
class CommissionStatsResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'total_commission', description: '累计佣金(分)', type: 'integer')]
    private int $totalCommission;

    #[OA\Property(property: 'settled_commission', description: '已结算佣金(分)', type: 'integer')]
    private int $settledCommission;

    #[OA\Property(property: 'pending_commission', description: '待结算佣金(分)', type: 'integer')]
    private int $pendingCommission;

    #[OA\Property(property: 'available_withdraw', description: '可提现金额(分)', type: 'integer')]
    private int $availableWithdraw;

    #[OA\Property(property: 'today_commission', description: '今日佣金(分)', type: 'integer')]
    private int $todayCommission;

    #[OA\Property(property: 'month_commission', description: '本月佣金(分)', type: 'integer')]
    private int $monthCommission;

    #[OA\Property(property: 'total_orders', description: '累计分销订单', type: 'integer')]
    private int $totalOrders;

    public function getTotalCommission(): int
    {
        return $this->totalCommission;
    }

    public function setTotalCommission(int $totalCommission): void
    {
        $this->totalCommission = $totalCommission;
    }

    public function getSettledCommission(): int
    {
        return $this->settledCommission;
    }

    public function setSettledCommission(int $settledCommission): void
    {
        $this->settledCommission = $settledCommission;
    }

    public function getPendingCommission(): int
    {
        return $this->pendingCommission;
    }

    public function setPendingCommission(int $pendingCommission): void
    {
        $this->pendingCommission = $pendingCommission;
    }

    public function getAvailableWithdraw(): int
    {
        return $this->availableWithdraw;
    }

    public function setAvailableWithdraw(int $availableWithdraw): void
    {
        $this->availableWithdraw = $availableWithdraw;
    }

    public function getTodayCommission(): int
    {
        return $this->todayCommission;
    }

    public function setTodayCommission(int $todayCommission): void
    {
        $this->todayCommission = $todayCommission;
    }

    public function getMonthCommission(): int
    {
        return $this->monthCommission;
    }

    public function setMonthCommission(int $monthCommission): void
    {
        $this->monthCommission = $monthCommission;
    }

    public function getTotalOrders(): int
    {
        return $this->totalOrders;
    }

    public function setTotalOrders(int $totalOrders): void
    {
        $this->totalOrders = $totalOrders;
    }
}
