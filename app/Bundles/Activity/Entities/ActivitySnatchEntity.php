<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ActivitySnatchEntity')]
class ActivitySnatchEntity
{
    use DTOHelper;

    const string getLogId = 'log_id';

    const string getSnatchId = 'snatch_id'; // 夺宝ID

    const string getUserId = 'user_id'; // 用户ID

    const string getBidPrice = 'bid_price'; // 出价

    const string getBidTime = 'bid_time'; // 出价时间

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'logId', description: '', type: 'integer')]
    private int $logId;

    #[OA\Property(property: 'snatchId', description: '夺宝ID', type: 'integer')]
    private int $snatchId;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'bidPrice', description: '出价', type: 'string')]
    private string $bidPrice;

    #[OA\Property(property: 'bidTime', description: '出价时间', type: 'integer')]
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
     * 获取夺宝ID
     */
    public function getSnatchId(): int
    {
        return $this->snatchId;
    }

    /**
     * 设置夺宝ID
     */
    public function setSnatchId(int $snatchId): void
    {
        $this->snatchId = $snatchId;
    }

    /**
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取出价
     */
    public function getBidPrice(): string
    {
        return $this->bidPrice;
    }

    /**
     * 设置出价
     */
    public function setBidPrice(string $bidPrice): void
    {
        $this->bidPrice = $bidPrice;
    }

    /**
     * 获取出价时间
     */
    public function getBidTime(): int
    {
        return $this->bidTime;
    }

    /**
     * 设置出价时间
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
