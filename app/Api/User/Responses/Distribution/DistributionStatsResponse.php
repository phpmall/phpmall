<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Distribution;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'DistributionStatsResponse')]
class DistributionStatsResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'today_orders', description: '今日订单数', type: 'integer')]
    private int $todayOrders;

    #[OA\Property(property: 'today_commission', description: '今日佣金(分)', type: 'integer')]
    private int $todayCommission;

    #[OA\Property(property: 'month_orders', description: '本月订单数', type: 'integer')]
    private int $monthOrders;

    #[OA\Property(property: 'month_commission', description: '本月佣金(分)', type: 'integer')]
    private int $monthCommission;

    #[OA\Property(property: 'total_orders', description: '累计订单数', type: 'integer')]
    private int $totalOrders;

    #[OA\Property(property: 'total_commission', description: '累计佣金(分)', type: 'integer')]
    private int $totalCommission;

    #[OA\Property(property: 'team_count', description: '团队人数', type: 'integer')]
    private int $teamCount;

    #[OA\Property(property: 'direct_count', description: '直推人数', type: 'integer')]
    private int $directCount;

    #[OA\Property(property: 'indirect_count', description: '间推人数', type: 'integer')]
    private int $indirectCount;

    public function getTodayOrders(): int
    {
        return $this->todayOrders;
    }

    public function setTodayOrders(int $todayOrders): void
    {
        $this->todayOrders = $todayOrders;
    }

    public function getTodayCommission(): int
    {
        return $this->todayCommission;
    }

    public function setTodayCommission(int $todayCommission): void
    {
        $this->todayCommission = $todayCommission;
    }

    public function getMonthOrders(): int
    {
        return $this->monthOrders;
    }

    public function setMonthOrders(int $monthOrders): void
    {
        $this->monthOrders = $monthOrders;
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

    public function getTotalCommission(): int
    {
        return $this->totalCommission;
    }

    public function setTotalCommission(int $totalCommission): void
    {
        $this->totalCommission = $totalCommission;
    }

    public function getTeamCount(): int
    {
        return $this->teamCount;
    }

    public function setTeamCount(int $teamCount): void
    {
        $this->teamCount = $teamCount;
    }

    public function getDirectCount(): int
    {
        return $this->directCount;
    }

    public function setDirectCount(int $directCount): void
    {
        $this->directCount = $directCount;
    }

    public function getIndirectCount(): int
    {
        return $this->indirectCount;
    }

    public function setIndirectCount(int $indirectCount): void
    {
        $this->indirectCount = $indirectCount;
    }
}
