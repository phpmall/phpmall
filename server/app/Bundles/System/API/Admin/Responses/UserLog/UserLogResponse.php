<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Responses\UserLog;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserLogResponse')]
class UserLogResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'eventType', description: '事件类型，用于区分不同的用户操作或系统事件', type: 'string')]
    private string $eventType;

    #[OA\Property(property: 'eventTime', description: '事件发生的时间', type: 'string')]
    private string $eventTime;

    #[OA\Property(property: 'eventDetails', description: '事件的详细信息，推荐json格式', type: 'string')]
    private string $eventDetails;

    #[OA\Property(property: 'ipAddress', description: '用户的IP地址', type: 'string')]
    private string $ipAddress;

    #[OA\Property(property: 'userAgent', description: '用户代理字符串', type: 'string')]
    private string $userAgent;

    #[OA\Property(property: 'createdAt', description: '', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '', type: 'string')]
    private string $deletedAt;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     * 获取事件类型，用于区分不同的用户操作或系统事件
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }

    /**
     * 设置事件类型，用于区分不同的用户操作或系统事件
     */
    public function setEventType(string $eventType): void
    {
        $this->eventType = $eventType;
    }

    /**
     * 获取事件发生的时间
     */
    public function getEventTime(): string
    {
        return $this->eventTime;
    }

    /**
     * 设置事件发生的时间
     */
    public function setEventTime(string $eventTime): void
    {
        $this->eventTime = $eventTime;
    }

    /**
     * 获取事件的详细信息，推荐json格式
     */
    public function getEventDetails(): string
    {
        return $this->eventDetails;
    }

    /**
     * 设置事件的详细信息，推荐json格式
     */
    public function setEventDetails(string $eventDetails): void
    {
        $this->eventDetails = $eventDetails;
    }

    /**
     * 获取用户的IP地址
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * 设置用户的IP地址
     */
    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * 获取用户代理字符串
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * 设置用户代理字符串
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
