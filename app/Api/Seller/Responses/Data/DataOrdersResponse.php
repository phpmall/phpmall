<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Data;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerDataOrdersResponse')]
class DataOrdersResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'period', description: '统计周期', type: 'string')]
    private string $period;

    #[OA\Property(property: 'order_stats', description: '订单统计数据', type: 'array', items: new OA\Items(type: 'object'))]
    private array $orderStats;

    #[OA\Property(property: 'refund_stats', description: '退款统计数据', type: 'array', items: new OA\Items(type: 'object'))]
    private array $refundStats;

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    public function getOrderStats(): array
    {
        return $this->orderStats;
    }

    public function setOrderStats(array $orderStats): void
    {
        $this->orderStats = $orderStats;
    }

    public function getRefundStats(): array
    {
        return $this->refundStats;
    }

    public function setRefundStats(array $refundStats): void
    {
        $this->refundStats = $refundStats;
    }
}
