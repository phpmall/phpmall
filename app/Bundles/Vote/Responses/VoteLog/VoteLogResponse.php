<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Responses\VoteLog;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'VoteLogResponse')]
class VoteLogResponse
{
    use DTOHelper;

    #[OA\Property(property: 'logId', description: '', type: 'integer')]
    private int $logId;

    #[OA\Property(property: 'voteId', description: '投票ID', type: 'integer')]
    private int $voteId;

    #[OA\Property(property: 'ipAddress', description: 'IP地址', type: 'string')]
    private string $ipAddress;

    #[OA\Property(property: 'voteTime', description: '投票时间', type: 'integer')]
    private int $voteTime;

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
     * 获取投票ID
     */
    public function getVoteId(): int
    {
        return $this->voteId;
    }

    /**
     * 设置投票ID
     */
    public function setVoteId(int $voteId): void
    {
        $this->voteId = $voteId;
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
     * 获取投票时间
     */
    public function getVoteTime(): int
    {
        return $this->voteTime;
    }

    /**
     * 设置投票时间
     */
    public function setVoteTime(int $voteTime): void
    {
        $this->voteTime = $voteTime;
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
