<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Coupon;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerCouponStatsResponse')]
class CouponStatsResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'coupon_id', description: '优惠券ID', type: 'integer')]
    private int $couponId;

    #[OA\Property(property: 'total_issued', description: '总发放数量', type: 'integer')]
    private int $totalIssued;

    #[OA\Property(property: 'total_used', description: '总使用数量', type: 'integer')]
    private int $totalUsed;

    #[OA\Property(property: 'total_expired', description: '总过期数量', type: 'integer')]
    private int $totalExpired;

    #[OA\Property(property: 'usage_rate', description: '使用率(百分比)', type: 'number', format: 'float')]
    private float $usageRate;

    #[OA\Property(property: 'total_discount_amount', description: '总优惠金额(分)', type: 'integer')]
    private int $totalDiscountAmount;

    #[OA\Property(property: 'total_order_amount', description: '总订单金额(分)', type: 'integer')]
    private int $totalOrderAmount;

    public function getCouponId(): int
    {
        return $this->couponId;
    }

    public function setCouponId(int $couponId): void
    {
        $this->couponId = $couponId;
    }

    public function getTotalIssued(): int
    {
        return $this->totalIssued;
    }

    public function setTotalIssued(int $totalIssued): void
    {
        $this->totalIssued = $totalIssued;
    }

    public function getTotalUsed(): int
    {
        return $this->totalUsed;
    }

    public function setTotalUsed(int $totalUsed): void
    {
        $this->totalUsed = $totalUsed;
    }

    public function getTotalExpired(): int
    {
        return $this->totalExpired;
    }

    public function setTotalExpired(int $totalExpired): void
    {
        $this->totalExpired = $totalExpired;
    }

    public function getUsageRate(): float
    {
        return $this->usageRate;
    }

    public function setUsageRate(float $usageRate): void
    {
        $this->usageRate = $usageRate;
    }

    public function getTotalDiscountAmount(): int
    {
        return $this->totalDiscountAmount;
    }

    public function setTotalDiscountAmount(int $totalDiscountAmount): void
    {
        $this->totalDiscountAmount = $totalDiscountAmount;
    }

    public function getTotalOrderAmount(): int
    {
        return $this->totalOrderAmount;
    }

    public function setTotalOrderAmount(int $totalOrderAmount): void
    {
        $this->totalOrderAmount = $totalOrderAmount;
    }
}
