<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ActivityEntity')]
class ActivityEntity
{
    use DTOHelper;

    const string getActId = 'act_id';

    const string getActName = 'act_name'; // 活动名称

    const string getStartTime = 'start_time'; // 开始时间

    const string getEndTime = 'end_time'; // 结束时间

    const string getUserRank = 'user_rank'; // 用户等级

    const string getActRange = 'act_range'; // 活动范围

    const string getActRangeExt = 'act_range_ext'; // 活动范围扩展

    const string getMinAmount = 'min_amount'; // 最小金额

    const string getMaxAmount = 'max_amount'; // 最大金额

    const string getActType = 'act_type'; // 活动类型

    const string getActTypeExt = 'act_type_ext'; // 活动类型扩展

    const string getGift = 'gift'; // 赠品

    const string getSortOrder = 'sort_order'; // 排序顺序

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'actId', description: '', type: 'integer')]
    private int $actId;

    #[OA\Property(property: 'actName', description: '活动名称', type: 'string')]
    private string $actName;

    #[OA\Property(property: 'startTime', description: '开始时间', type: 'integer')]
    private int $startTime;

    #[OA\Property(property: 'endTime', description: '结束时间', type: 'integer')]
    private int $endTime;

    #[OA\Property(property: 'userRank', description: '用户等级', type: 'string')]
    private string $userRank;

    #[OA\Property(property: 'actRange', description: '活动范围', type: 'integer')]
    private int $actRange;

    #[OA\Property(property: 'actRangeExt', description: '活动范围扩展', type: 'string')]
    private string $actRangeExt;

    #[OA\Property(property: 'minAmount', description: '最小金额', type: 'string')]
    private string $minAmount;

    #[OA\Property(property: 'maxAmount', description: '最大金额', type: 'string')]
    private string $maxAmount;

    #[OA\Property(property: 'actType', description: '活动类型', type: 'integer')]
    private int $actType;

    #[OA\Property(property: 'actTypeExt', description: '活动类型扩展', type: 'string')]
    private string $actTypeExt;

    #[OA\Property(property: 'gift', description: '赠品', type: 'string')]
    private string $gift;

    #[OA\Property(property: 'sortOrder', description: '排序顺序', type: 'integer')]
    private int $sortOrder;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getActId(): int
    {
        return $this->actId;
    }

    /**
     * 设置
     */
    public function setActId(int $actId): void
    {
        $this->actId = $actId;
    }

    /**
     * 获取活动名称
     */
    public function getActName(): string
    {
        return $this->actName;
    }

    /**
     * 设置活动名称
     */
    public function setActName(string $actName): void
    {
        $this->actName = $actName;
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
     * 获取用户等级
     */
    public function getUserRank(): string
    {
        return $this->userRank;
    }

    /**
     * 设置用户等级
     */
    public function setUserRank(string $userRank): void
    {
        $this->userRank = $userRank;
    }

    /**
     * 获取活动范围
     */
    public function getActRange(): int
    {
        return $this->actRange;
    }

    /**
     * 设置活动范围
     */
    public function setActRange(int $actRange): void
    {
        $this->actRange = $actRange;
    }

    /**
     * 获取活动范围扩展
     */
    public function getActRangeExt(): string
    {
        return $this->actRangeExt;
    }

    /**
     * 设置活动范围扩展
     */
    public function setActRangeExt(string $actRangeExt): void
    {
        $this->actRangeExt = $actRangeExt;
    }

    /**
     * 获取最小金额
     */
    public function getMinAmount(): string
    {
        return $this->minAmount;
    }

    /**
     * 设置最小金额
     */
    public function setMinAmount(string $minAmount): void
    {
        $this->minAmount = $minAmount;
    }

    /**
     * 获取最大金额
     */
    public function getMaxAmount(): string
    {
        return $this->maxAmount;
    }

    /**
     * 设置最大金额
     */
    public function setMaxAmount(string $maxAmount): void
    {
        $this->maxAmount = $maxAmount;
    }

    /**
     * 获取活动类型
     */
    public function getActType(): int
    {
        return $this->actType;
    }

    /**
     * 设置活动类型
     */
    public function setActType(int $actType): void
    {
        $this->actType = $actType;
    }

    /**
     * 获取活动类型扩展
     */
    public function getActTypeExt(): string
    {
        return $this->actTypeExt;
    }

    /**
     * 设置活动类型扩展
     */
    public function setActTypeExt(string $actTypeExt): void
    {
        $this->actTypeExt = $actTypeExt;
    }

    /**
     * 获取赠品
     */
    public function getGift(): string
    {
        return $this->gift;
    }

    /**
     * 设置赠品
     */
    public function setGift(string $gift): void
    {
        $this->gift = $gift;
    }

    /**
     * 获取排序顺序
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * 设置排序顺序
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
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
