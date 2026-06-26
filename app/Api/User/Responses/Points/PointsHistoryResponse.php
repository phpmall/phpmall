<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Points;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PointsHistoryResponse')]
class PointsHistoryResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '记录ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '变动类型:earn,use,expire,refund', type: 'string')]
    private string $type;

    #[OA\Property(property: 'points', description: '变动积分', type: 'integer')]
    private int $points;

    #[OA\Property(property: 'balance_before', description: '变动前积分', type: 'integer')]
    private int $balanceBefore;

    #[OA\Property(property: 'balance_after', description: '变动后积分', type: 'integer')]
    private int $balanceAfter;

    #[OA\Property(property: 'description', description: '变动描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'source_type', description: '来源类型', type: 'string', nullable: true)]
    private ?string $sourceType;

    #[OA\Property(property: 'source_id', description: '来源ID', type: 'integer', nullable: true)]
    private ?int $sourceId;

    #[OA\Property(property: 'created_at', description: '变动时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getBalanceBefore(): int
    {
        return $this->balanceBefore;
    }

    public function setBalanceBefore(int $balanceBefore): void
    {
        $this->balanceBefore = $balanceBefore;
    }

    public function getBalanceAfter(): int
    {
        return $this->balanceAfter;
    }

    public function setBalanceAfter(int $balanceAfter): void
    {
        $this->balanceAfter = $balanceAfter;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSourceType(): ?string
    {
        return $this->sourceType;
    }

    public function setSourceType(?string $sourceType): void
    {
        $this->sourceType = $sourceType;
    }

    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    public function setSourceId(?int $sourceId): void
    {
        $this->sourceId = $sourceId;
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
