<?php

declare(strict_types=1);

namespace App\Bundles\User\Responses\UserRank;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserRankResponse')]
class UserRankResponse
{
    use DTOHelper;

    #[OA\Property(property: 'rankId', description: '', type: 'integer')]
    private int $rankId;

    #[OA\Property(property: 'rankName', description: '等级名称', type: 'string')]
    private string $rankName;

    #[OA\Property(property: 'minPoints', description: '最小积分', type: 'integer')]
    private int $minPoints;

    #[OA\Property(property: 'maxPoints', description: '最大积分', type: 'integer')]
    private int $maxPoints;

    #[OA\Property(property: 'discount', description: '折扣', type: 'integer')]
    private int $discount;

    #[OA\Property(property: 'showPrice', description: '是否显示价格', type: 'integer')]
    private int $showPrice;

    #[OA\Property(property: 'specialRank', description: '是否特殊等级', type: 'integer')]
    private int $specialRank;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getRankId(): int
    {
        return $this->rankId;
    }

    /**
     * 设置
     */
    public function setRankId(int $rankId): void
    {
        $this->rankId = $rankId;
    }

    /**
     * 获取等级名称
     */
    public function getRankName(): string
    {
        return $this->rankName;
    }

    /**
     * 设置等级名称
     */
    public function setRankName(string $rankName): void
    {
        $this->rankName = $rankName;
    }

    /**
     * 获取最小积分
     */
    public function getMinPoints(): int
    {
        return $this->minPoints;
    }

    /**
     * 设置最小积分
     */
    public function setMinPoints(int $minPoints): void
    {
        $this->minPoints = $minPoints;
    }

    /**
     * 获取最大积分
     */
    public function getMaxPoints(): int
    {
        return $this->maxPoints;
    }

    /**
     * 设置最大积分
     */
    public function setMaxPoints(int $maxPoints): void
    {
        $this->maxPoints = $maxPoints;
    }

    /**
     * 获取折扣
     */
    public function getDiscount(): int
    {
        return $this->discount;
    }

    /**
     * 设置折扣
     */
    public function setDiscount(int $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * 获取是否显示价格
     */
    public function getShowPrice(): int
    {
        return $this->showPrice;
    }

    /**
     * 设置是否显示价格
     */
    public function setShowPrice(int $showPrice): void
    {
        $this->showPrice = $showPrice;
    }

    /**
     * 获取是否特殊等级
     */
    public function getSpecialRank(): int
    {
        return $this->specialRank;
    }

    /**
     * 设置是否特殊等级
     */
    public function setSpecialRank(int $specialRank): void
    {
        $this->specialRank = $specialRank;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedTime(): string
    {
        return $this->createdTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedTime(string $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedTime(): string
    {
        return $this->updatedTime;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedTime(string $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}
