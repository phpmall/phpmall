<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Responses\AdminLog;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminLogResponse')]
class AdminLogResponse
{
    use DTOHelper;

    #[OA\Property(property: 'logId', description: '', type: 'integer')]
    private int $logId;

    #[OA\Property(property: 'logTime', description: '日志时间', type: 'integer')]
    private int $logTime;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'logInfo', description: '日志信息', type: 'string')]
    private string $logInfo;

    #[OA\Property(property: 'ipAddress', description: 'IP地址', type: 'string')]
    private string $ipAddress;

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
     * 获取日志时间
     */
    public function getLogTime(): int
    {
        return $this->logTime;
    }

    /**
     * 设置日志时间
     */
    public function setLogTime(int $logTime): void
    {
        $this->logTime = $logTime;
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
     * 获取日志信息
     */
    public function getLogInfo(): string
    {
        return $this->logInfo;
    }

    /**
     * 设置日志信息
     */
    public function setLogInfo(string $logInfo): void
    {
        $this->logInfo = $logInfo;
    }

    /**
     * 获取IP地址
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * 设置IP地址
     */
    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
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
