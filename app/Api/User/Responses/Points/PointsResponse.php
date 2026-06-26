<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Points;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PointsResponse')]
class PointsResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'total_points', description: '总积分', type: 'integer')]
    private int $totalPoints;

    #[OA\Property(property: 'available_points', description: '可用积分', type: 'integer')]
    private int $availablePoints;

    #[OA\Property(property: 'frozen_points', description: '冻结积分', type: 'integer')]
    private int $frozenPoints;

    #[OA\Property(property: 'total_earned', description: '累计获得积分', type: 'integer')]
    private int $totalEarned;

    #[OA\Property(property: 'total_used', description: '累计使用积分', type: 'integer')]
    private int $totalUsed;

    #[OA\Property(property: 'expire_soon', description: '即将过期积分', type: 'integer')]
    private int $expireSoon;

    #[OA\Property(property: 'expire_date', description: '过期日期', type: 'string', format: 'date', nullable: true)]
    private ?string $expireDate;

    public function getTotalPoints(): int
    {
        return $this->totalPoints;
    }

    public function setTotalPoints(int $totalPoints): void
    {
        $this->totalPoints = $totalPoints;
    }

    public function getAvailablePoints(): int
    {
        return $this->availablePoints;
    }

    public function setAvailablePoints(int $availablePoints): void
    {
        $this->availablePoints = $availablePoints;
    }

    public function getFrozenPoints(): int
    {
        return $this->frozenPoints;
    }

    public function setFrozenPoints(int $frozenPoints): void
    {
        $this->frozenPoints = $frozenPoints;
    }

    public function getTotalEarned(): int
    {
        return $this->totalEarned;
    }

    public function setTotalEarned(int $totalEarned): void
    {
        $this->totalEarned = $totalEarned;
    }

    public function getTotalUsed(): int
    {
        return $this->totalUsed;
    }

    public function setTotalUsed(int $totalUsed): void
    {
        $this->totalUsed = $totalUsed;
    }

    public function getExpireSoon(): int
    {
        return $this->expireSoon;
    }

    public function setExpireSoon(int $expireSoon): void
    {
        $this->expireSoon = $expireSoon;
    }

    public function getExpireDate(): ?string
    {
        return $this->expireDate;
    }

    public function setExpireDate(?string $expireDate): void
    {
        $this->expireDate = $expireDate;
    }
}
