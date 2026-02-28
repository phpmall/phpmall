<?php

declare(strict_types=1);

namespace App\Bundles\User\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAffiliateEntity')]
class UserAffiliateEntity
{
    use DTOHelper;

    const string getLogId = 'log_id';

    const string getOrderId = 'order_id'; // 订单ID

    const string getTime = 'time'; // 时间

    const string getUserId = 'user_id'; // 用户ID

    const string getUserName = 'user_name'; // 用户名

    const string getMoney = 'money'; // 金额

    const string getPoint = 'point'; // 积分

    const string getSeparateType = 'separate_type'; // 分成类型

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'logId', description: '', type: 'integer')]
    private int $logId;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'time', description: '时间', type: 'integer')]
    private int $time;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'userName', description: '用户名', type: 'string')]
    private string $userName;

    #[OA\Property(property: 'money', description: '金额', type: 'string')]
    private string $money;

    #[OA\Property(property: 'point', description: '积分', type: 'integer')]
    private int $point;

    #[OA\Property(property: 'separateType', description: '分成类型', type: 'integer')]
    private int $separateType;

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
     * 获取订单ID
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置订单ID
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * 获取时间
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * 设置时间
     */
    public function setTime(int $time): void
    {
        $this->time = $time;
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
     * 获取用户名
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * 设置用户名
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * 获取金额
     */
    public function getMoney(): string
    {
        return $this->money;
    }

    /**
     * 设置金额
     */
    public function setMoney(string $money): void
    {
        $this->money = $money;
    }

    /**
     * 获取积分
     */
    public function getPoint(): int
    {
        return $this->point;
    }

    /**
     * 设置积分
     */
    public function setPoint(int $point): void
    {
        $this->point = $point;
    }

    /**
     * 获取分成类型
     */
    public function getSeparateType(): int
    {
        return $this->separateType;
    }

    /**
     * 设置分成类型
     */
    public function setSeparateType(int $separateType): void
    {
        $this->separateType = $separateType;
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
