<?php

declare(strict_types=1);

namespace App\Api\Shop\Responses\Coupon;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopCouponResponse')]
class CouponResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '优惠券ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '优惠券名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'type', description: '优惠券类型:1满减,2折扣', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'type_name', description: '类型名称', type: 'string')]
    private string $typeName;

    #[OA\Property(property: 'min_amount', description: '最低消费金额(分)', type: 'integer', nullable: true)]
    private ?int $minAmount;

    #[OA\Property(property: 'discount_amount', description: '优惠金额(分)', type: 'integer', nullable: true)]
    private ?int $discountAmount;

    #[OA\Property(property: 'discount_rate', description: '折扣率(百分比)', type: 'integer', nullable: true)]
    private ?int $discountRate;

    #[OA\Property(property: 'shop_id', description: '店铺ID', type: 'integer', nullable: true)]
    private ?int $shopId;

    #[OA\Property(property: 'shop_name', description: '店铺名称', type: 'string', nullable: true)]
    private ?string $shopName;

    #[OA\Property(property: 'total_count', description: '发放总量', type: 'integer')]
    private int $totalCount;

    #[OA\Property(property: 'received_count', description: '已领取数量', type: 'integer')]
    private int $receivedCount;

    #[OA\Property(property: 'start_time', description: '开始时间', type: 'string', format: 'date-time')]
    private string $startTime;

    #[OA\Property(property: 'end_time', description: '结束时间', type: 'string', format: 'date-time')]
    private string $endTime;

    #[OA\Property(property: 'status', description: '状态:0未开始,1进行中,2已结束', type: 'integer')]
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

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): void
    {
        $this->typeName = $typeName;
    }

    public function getMinAmount(): ?int
    {
        return $this->minAmount;
    }

    public function setMinAmount(?int $minAmount): void
    {
        $this->minAmount = $minAmount;
    }

    public function getDiscountAmount(): ?int
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?int $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getDiscountRate(): ?int
    {
        return $this->discountRate;
    }

    public function setDiscountRate(?int $discountRate): void
    {
        $this->discountRate = $discountRate;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function setShopId(?int $shopId): void
    {
        $this->shopId = $shopId;
    }

    public function getShopName(): ?string
    {
        return $this->shopName;
    }

    public function setShopName(?string $shopName): void
    {
        $this->shopName = $shopName;
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
