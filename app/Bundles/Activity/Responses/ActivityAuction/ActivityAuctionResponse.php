<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Responses\ActivityAuction;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ActivityAuctionResponse')]
class ActivityAuctionResponse
{
    use DTOHelper;

    #[OA\Property(property: 'logId', description: '', type: 'integer')]
    private int $logId;

    #[OA\Property(property: 'actId', description: '活动ID', type: 'integer')]
    private int $actId;

    #[OA\Property(property: 'bidUser', description: '竞价用户', type: 'integer')]
    private int $bidUser;

    #[OA\Property(property: 'bidPrice', description: '竞价金额', type: 'string')]
    private string $bidPrice;

    #[OA\Property(property: 'bidTime', description: '竞价时间', type: 'integer')]
    private int $bidTime;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getLogId(): int
    {
        return $this->logId;
    }

    /**
     * 设置
     */
    public function setLogId(int $logId): void
    {
        $this->logId = $logId;
    }

    /**
     * 获取活动ID
     */
    public function getActId(): int
    {
        return $this->actId;
    }

    /**
     * 设置活动ID
     */
    public function setActId(int $actId): void
    {
        $this->actId = $actId;
    }

    /**
     * 获取竞价用户
     */
    public function getBidUser(): int
    {
        return $this->bidUser;
    }

    /**
     * 设置竞价用户
     */
    public function setBidUser(int $bidUser): void
    {
        $this->bidUser = $bidUser;
    }

    /**
     * 获取竞价金额
     */
    public function getBidPrice(): string
    {
        return $this->bidPrice;
    }

    /**
     * 设置竞价金额
     */
    public function setBidPrice(string $bidPrice): void
    {
        $this->bidPrice = $bidPrice;
    }

    /**
     * 获取竞价时间
     */
    public function getBidTime(): int
    {
        return $this->bidTime;
    }

    /**
     * 设置竞价时间
     */
    public function setBidTime(int $bidTime): void
    {
        $this->bidTime = $bidTime;
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
