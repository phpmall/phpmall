<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Coupon;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CouponResponse')]
class CouponResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '优惠券ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '优惠券名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'type', description: '优惠券类型:full_reduction,discount', type: 'string')]
    private string $type;

    #[OA\Property(property: 'amount', description: '优惠金额(分)或折扣率', type: 'integer')]
    private int $amount;

    #[OA\Property(property: 'min_order_amount', description: '最低订单金额(分)', type: 'integer', nullable: true)]
    private ?int $minOrderAmount;

    #[OA\Property(property: 'description', description: '优惠券描述', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'start_time', description: '生效时间', type: 'string', format: 'date-time')]
    private string $startTime;

    #[OA\Property(property: 'end_time', description: '过期时间', type: 'string', format: 'date-time')]
    private string $endTime;

    #[OA\Property(property: 'total_count', description: '总发行量', type: 'integer')]
    private int $totalCount;

    #[OA\Property(property: 'received_count', description: '已领取数量', type: 'integer')]
    private int $receivedCount;

    #[OA\Property(property: 'is_received', description: '当前用户是否已领取:0否，1是', type: 'integer')]
    private int $isReceived;

    #[OA\Property(property: 'scope', description: '适用范围:all,category,product', type: 'string')]
    private string $scope;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
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

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public function getReceivedCount(): int
    {
        return $this->receivedCount;
    }

    public function setReceivedCount(int $receivedCount): void
    {
        $this->receivedCount = $receivedCount;
    }

    public function getIsReceived(): int
    {
        return $this->isReceived;
    }

    public function setIsReceived(int $isReceived): void
    {
        $this->isReceived = $isReceived;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function setScope(string $scope): void
    {
        $this->scope = $scope;
    }
}
