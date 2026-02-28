<?php

declare(strict_types=1);

namespace App\Bundles\User\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserBonusEntity')]
class UserBonusEntity
{
    use DTOHelper;

    const string getBonusId = 'bonus_id';

    const string getBonusTypeId = 'bonus_type_id'; // 红包类型ID

    const string getBonusSn = 'bonus_sn'; // 红包序列号

    const string getUserId = 'user_id'; // 用户ID

    const string getUsedTime = 'used_time'; // 使用时间

    const string getOrderId = 'order_id'; // 订单ID

    const string getEmailed = 'emailed'; // 是否已发送邮件

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'bonusId', description: '', type: 'integer')]
    private int $bonusId;

    #[OA\Property(property: 'bonusTypeId', description: '红包类型ID', type: 'integer')]
    private int $bonusTypeId;

    #[OA\Property(property: 'bonusSn', description: '红包序列号', type: 'integer')]
    private int $bonusSn;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'usedTime', description: '使用时间', type: 'integer')]
    private int $usedTime;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'emailed', description: '是否已发送邮件', type: 'integer')]
    private int $emailed;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getBonusId(): int
    {
        return $this->bonusId;
    }

    /**
     * 设置
     */
    public function setBonusId(int $bonusId): void
    {
        $this->bonusId = $bonusId;
    }

    /**
     * 获取红包类型ID
     */
    public function getBonusTypeId(): int
    {
        return $this->bonusTypeId;
    }

    /**
     * 设置红包类型ID
     */
    public function setBonusTypeId(int $bonusTypeId): void
    {
        $this->bonusTypeId = $bonusTypeId;
    }

    /**
     * 获取红包序列号
     */
    public function getBonusSn(): int
    {
        return $this->bonusSn;
    }

    /**
     * 设置红包序列号
     */
    public function setBonusSn(int $bonusSn): void
    {
        $this->bonusSn = $bonusSn;
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
     * 获取使用时间
     */
    public function getUsedTime(): int
    {
        return $this->usedTime;
    }

    /**
     * 设置使用时间
     */
    public function setUsedTime(int $usedTime): void
    {
        $this->usedTime = $usedTime;
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
     * 获取是否已发送邮件
     */
    public function getEmailed(): int
    {
        return $this->emailed;
    }

    /**
     * 设置是否已发送邮件
     */
    public function setEmailed(int $emailed): void
    {
        $this->emailed = $emailed;
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
