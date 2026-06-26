<?php

declare(strict_types=1);

namespace App\Api\User\Responses\MemberLevel;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MemberLevelResponse')]
class MemberLevelResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '等级ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '等级名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'level', description: '等级序号', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'icon', description: '等级图标', type: 'string', nullable: true)]
    private ?string $icon;

    #[OA\Property(property: 'min_points', description: '最低积分要求', type: 'integer')]
    private int $minPoints;

    #[OA\Property(property: 'max_points', description: '最高积分上限', type: 'integer', nullable: true)]
    private ?int $maxPoints;

    #[OA\Property(property: 'discount_rate', description: '折扣率', type: 'number', format: 'float')]
    private float $discountRate;

    #[OA\Property(property: 'is_current', description: '是否当前等级:0否，1是', type: 'integer')]
    private int $isCurrent;

    #[OA\Property(property: 'next_level_points', description: '升级所需积分', type: 'integer', nullable: true)]
    private ?int $nextLevelPoints;

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

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    public function getMinPoints(): int
    {
        return $this->minPoints;
    }

    public function setMinPoints(int $minPoints): void
    {
        $this->minPoints = $minPoints;
    }

    public function getMaxPoints(): ?int
    {
        return $this->maxPoints;
    }

    public function setMaxPoints(?int $maxPoints): void
    {
        $this->maxPoints = $maxPoints;
    }

    public function getDiscountRate(): float
    {
        return $this->discountRate;
    }

    public function setDiscountRate(float $discountRate): void
    {
        $this->discountRate = $discountRate;
    }

    public function getIsCurrent(): int
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(int $isCurrent): void
    {
        $this->isCurrent = $isCurrent;
    }

    public function getNextLevelPoints(): ?int
    {
        return $this->nextLevelPoints;
    }

    public function setNextLevelPoints(?int $nextLevelPoints): void
    {
        $this->nextLevelPoints = $nextLevelPoints;
    }
}
