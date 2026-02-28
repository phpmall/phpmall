<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'VoteOptionEntity')]
class VoteOptionEntity
{
    use DTOHelper;

    const string getOptionId = 'option_id';

    const string getVoteId = 'vote_id'; // 投票ID

    const string getOptionName = 'option_name'; // 选项名称

    const string getOptionCount = 'option_count'; // 选项票数

    const string getOptionOrder = 'option_order'; // 选项顺序

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'optionId', description: '', type: 'integer')]
    private int $optionId;

    #[OA\Property(property: 'voteId', description: '投票ID', type: 'integer')]
    private int $voteId;

    #[OA\Property(property: 'optionName', description: '选项名称', type: 'string')]
    private string $optionName;

    #[OA\Property(property: 'optionCount', description: '选项票数', type: 'integer')]
    private int $optionCount;

    #[OA\Property(property: 'optionOrder', description: '选项顺序', type: 'integer')]
    private int $optionOrder;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getOptionId(): int
    {
        return $this->optionId;
    }

    /**
     * 设置
     */
    public function setOptionId(int $optionId): void
    {
        $this->optionId = $optionId;
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
     * 获取选项名称
     */
    public function getOptionName(): string
    {
        return $this->optionName;
    }

    /**
     * 设置选项名称
     */
    public function setOptionName(string $optionName): void
    {
        $this->optionName = $optionName;
    }

    /**
     * 获取选项票数
     */
    public function getOptionCount(): int
    {
        return $this->optionCount;
    }

    /**
     * 设置选项票数
     */
    public function setOptionCount(int $optionCount): void
    {
        $this->optionCount = $optionCount;
    }

    /**
     * 获取选项顺序
     */
    public function getOptionOrder(): int
    {
        return $this->optionOrder;
    }

    /**
     * 设置选项顺序
     */
    public function setOptionOrder(int $optionOrder): void
    {
        $this->optionOrder = $optionOrder;
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
