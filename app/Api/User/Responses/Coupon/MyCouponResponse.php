<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Coupon;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MyCouponResponse')]
class MyCouponResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '用户优惠券ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'coupon_id', description: '优惠券ID', type: 'integer')]
    private int $couponId;

    #[OA\Property(property: 'name', description: '优惠券名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'type', description: '优惠券类型', type: 'string')]
    private string $type;

    #[OA\Property(property: 'amount', description: '优惠金额(分)或折扣率', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'min_order_amount', description: '最低订单金额(分)', type: 'integer', nullable: true)]
    private ?int $minOrderAmount;

    #[OA\Property(property: 'status', description: '状态:0未使用,1已使用,2已过期', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'start_time', description: '生效时间', type: 'string', format: 'date-time')]
    private string $startTime;

    #[OA\Property(property: 'end_time', description: '过期时间', type: 'string', format: 'date-time')]
    private string $endTime;

    #[OA\Property(property: 'used_at', description: '使用时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $usedAt;

    #[OA\Property(property: 'order_id', description: '使用订单ID', type: 'integer', nullable: true)]
    private ?int $orderId;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCouponId(): int
    {
        return $this->couponId;
    }

    public function setCouponId(int $couponId): void
    {
        $this->couponId = $couponId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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

    public function getMinOrderAmount(): ?int
    {
        return $this->minOrderAmount;
    }

    public function setMinOrderAmount(?int $minOrderAmount): void
    {
        $this->minOrderAmount = $minOrderAmount;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function getUsedAt(): ?string
    {
        return $this->usedAt;
    }

    public function setUsedAt(?string $usedAt): void
    {
        $this->usedAt = $usedAt;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): void
    {
        $this->orderId = $orderId;
    }
}
