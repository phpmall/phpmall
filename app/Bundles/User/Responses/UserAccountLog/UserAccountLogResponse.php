<?php

declare(strict_types=1);

namespace App\Bundles\User\Responses\UserAccountLog;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAccountLogResponse')]
class UserAccountLogResponse
{
    use DTOHelper;

    #[OA\Property(property: 'logId', description: '', type: 'integer')]
    private int $logId;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'userMoney', description: '用户余额', type: 'string')]
    private string $userMoney;

    #[OA\Property(property: 'frozenMoney', description: '冻结金额', type: 'string')]
    private string $frozenMoney;

    #[OA\Property(property: 'rankPoints', description: '等级积分', type: 'integer')]
    private int $rankPoints;

    #[OA\Property(property: 'payPoints', description: '消费积分', type: 'integer')]
    private int $payPoints;

    #[OA\Property(property: 'changeTime', description: '变更时间', type: 'integer')]
    private int $changeTime;

    #[OA\Property(property: 'changeDesc', description: '变更描述', type: 'string')]
    private string $changeDesc;

    #[OA\Property(property: 'changeType', description: '变更类型', type: 'integer')]
    private int $changeType;

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
     * 获取用户余额
     */
    public function getUserMoney(): string
    {
        return $this->userMoney;
    }

    /**
     * 设置用户余额
     */
    public function setUserMoney(string $userMoney): void
    {
        $this->userMoney = $userMoney;
    }

    /**
     * 获取冻结金额
     */
    public function getFrozenMoney(): string
    {
        return $this->frozenMoney;
    }

    /**
     * 设置冻结金额
     */
    public function setFrozenMoney(string $frozenMoney): void
    {
        $this->frozenMoney = $frozenMoney;
    }

    /**
     * 获取等级积分
     */
    public function getRankPoints(): int
    {
        return $this->rankPoints;
    }

    /**
     * 设置等级积分
     */
    public function setRankPoints(int $rankPoints): void
    {
        $this->rankPoints = $rankPoints;
    }

    /**
     * 获取消费积分
     */
    public function getPayPoints(): int
    {
        return $this->payPoints;
    }

    /**
     * 设置消费积分
     */
    public function setPayPoints(int $payPoints): void
    {
        $this->payPoints = $payPoints;
    }

    /**
     * 获取变更时间
     */
    public function getChangeTime(): int
    {
        return $this->changeTime;
    }

    /**
     * 设置变更时间
     */
    public function setChangeTime(int $changeTime): void
    {
        $this->changeTime = $changeTime;
    }

    /**
     * 获取变更描述
     */
    public function getChangeDesc(): string
    {
        return $this->changeDesc;
    }

    /**
     * 设置变更描述
     */
    public function setChangeDesc(string $changeDesc): void
    {
        $this->changeDesc = $changeDesc;
    }

    /**
     * 获取变更类型
     */
    public function getChangeType(): int
    {
        return $this->changeType;
    }

    /**
     * 设置变更类型
     */
    public function setChangeType(int $changeType): void
    {
        $this->changeType = $changeType;
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
