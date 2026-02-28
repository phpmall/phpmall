<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Responses\Vote;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'VoteResponse')]
class VoteResponse
{
    use DTOHelper;

    #[OA\Property(property: 'voteId', description: '', type: 'integer')]
    private int $voteId;

    #[OA\Property(property: 'voteName', description: '投票名称', type: 'string')]
    private string $voteName;

    #[OA\Property(property: 'startTime', description: '开始时间', type: 'integer')]
    private int $startTime;

    #[OA\Property(property: 'endTime', description: '结束时间', type: 'integer')]
    private int $endTime;

    #[OA\Property(property: 'canMulti', description: '是否多选', type: 'integer')]
    private int $canMulti;

    #[OA\Property(property: 'voteCount', description: '投票次数', type: 'integer')]
    private int $voteCount;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getVoteId(): int
    {
        return $this->voteId;
    }

    /**
     * 设置
     */
    public function setVoteId(int $voteId): void
    {
        $this->voteId = $voteId;
    }

    /**
     * 获取投票名称
     */
    public function getVoteName(): string
    {
        return $this->voteName;
    }

    /**
     * 设置投票名称
     */
    public function setVoteName(string $voteName): void
    {
        $this->voteName = $voteName;
    }

    /**
     * 获取开始时间
     */
    public function getStartTime(): int
    {
        return $this->startTime;
    }

    /**
     * 设置开始时间
     */
    public function setStartTime(int $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取结束时间
     */
    public function getEndTime(): int
    {
        return $this->endTime;
    }

    /**
     * 设置结束时间
     */
    public function setEndTime(int $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * 获取是否多选
     */
    public function getCanMulti(): int
    {
        return $this->canMulti;
    }

    /**
     * 设置是否多选
     */
    public function setCanMulti(int $canMulti): void
    {
        $this->canMulti = $canMulti;
    }

    /**
     * 获取投票次数
     */
    public function getVoteCount(): int
    {
        return $this->voteCount;
    }

    /**
     * 设置投票次数
     */
    public function setVoteCount(int $voteCount): void
    {
        $this->voteCount = $voteCount;
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
