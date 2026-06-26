<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Coupon;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerCouponResponse')]
class CouponResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '优惠券ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '优惠券名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'type', description: '优惠券类型:1满减,2折扣,3无门槛', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'amount', description: '优惠金额(分)或折扣率', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'min_order_amount', description: '最低订单金额(分)', type: 'integer', nullable: true)]
    private ?int $minOrderAmount;

    #[OA\Property(property: 'total_quantity', description: '发放总量', type: 'integer')]
    private int $totalQuantity;

    #[OA\Property(property: 'remaining_quantity', description: '剩余数量', type: 'integer')]
    private int $remainingQuantity;

    #[OA\Property(property: 'start_time', description: '开始时间', type: 'string', format: 'date-time')]
    private string $startTime;

    #[OA\Property(property: 'end_time', description: '结束时间', type: 'string', format: 'date-time')]
    private string $endTime;

    #[OA\Property(property: 'status', description: '状态:0禁用,1启用', type: 'integer')]
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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

    public function getMinOrderAmount(): ?int
    {
        return $this->minOrderAmount;
    }

    public function setMinOrderAmount(?int $minOrderAmount): void
    {
        $this->minOrderAmount = $minOrderAmount;
    }

    public function getTotalQuantity(): int
    {
        return $this->totalQuantity;
    }

    public function setTotalQuantity(int $totalQuantity): void
    {
        $this->totalQuantity = $totalQuantity;
    }

    public function getRemainingQuantity(): int
    {
        return $this->remainingQuantity;
    }

    public function setRemainingQuantity(int $remainingQuantity): void
    {
        $this->remainingQuantity = $remainingQuantity;
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
