<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Data;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerDataOverviewResponse')]
class DataOverviewResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'total_orders', description: '累计订单数', type: 'integer')]
    private int $totalOrders;

    #[OA\Property(property: 'total_sales', description: '累计销售额(分)', type: 'integer')]
    private int $totalSales;

    #[OA\Property(property: 'total_products', description: '商品总数', type: 'integer')]
    private int $totalProducts;

    #[OA\Property(property: 'pending_orders', description: '待处理订单数', type: 'integer')]
    private int $pendingOrders;

    #[OA\Property(property: 'pending_refunds', description: '待处理退款数', type: 'integer')]
    private int $pendingRefunds;

    #[OA\Property(property: 'today_orders', description: '今日订单数', type: 'integer')]
    private int $todayOrders;

    #[OA\Property(property: 'today_sales', description: '今日销售额(分)', type: 'integer')]
    private int $todaySales;

    #[OA\Property(property: 'week_orders', description: '本周订单数', type: 'integer')]
    private int $weekOrders;

    #[OA\Property(property: 'week_sales', description: '本周销售额(分)', type: 'integer')]
    private int $weekSales;

    public function getTotalOrders(): int
    {
        return $this->totalOrders;
    }

    public function setTotalOrders(int $totalOrders): void
    {
        $this->totalOrders = $totalOrders;
    }

    public function getTotalSales(): int
    {
        return $this->totalSales;
    }

    public function setTotalSales(int $totalSales): void
    {
        $this->totalSales = $totalSales;
    }

    public function getTotalProducts(): int
    {
        return $this->totalProducts;
    }

    public function setTotalProducts(int $totalProducts): void
    {
        $this->totalProducts = $totalProducts;
    }

    public function getPendingOrders(): int
    {
        return $this->pendingOrders;
    }

    public function setPendingOrders(int $pendingOrders): void
    {
        $this->pendingOrders = $pendingOrders;
    }

    public function getPendingRefunds(): int
    {
        return $this->pendingRefunds;
    }

    public function setPendingRefunds(int $pendingRefunds): void
    {
        $this->pendingRefunds = $pendingRefunds;
    }

    public function getTodayOrders(): int
    {
        return $this->todayOrders;
    }

    public function setTodayOrders(int $todayOrders): void
    {
        $this->todayOrders = $todayOrders;
    }

    public function getTodaySales(): int
    {
        return $this->todaySales;
    }

    public function setTodaySales(int $todaySales): void
    {
        $this->todaySales = $todaySales;
    }

    public function getWeekOrders(): int
    {
        return $this->weekOrders;
    }

    public function setWeekOrders(int $weekOrders): void
    {
        $this->weekOrders = $weekOrders;
    }

    public function getWeekSales(): int
    {
        return $this->weekSales;
    }

    public function setWeekSales(int $weekSales): void
    {
        $this->weekSales = $weekSales;
    }
}
