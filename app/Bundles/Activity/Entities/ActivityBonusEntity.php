<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ActivityBonusEntity')]
class ActivityBonusEntity
{
    use DTOHelper;

    const string getTypeId = 'type_id';

    const string getTypeName = 'type_name'; // 红包类型名称

    const string getTypeMoney = 'type_money'; // 红包金额

    const string getSendType = 'send_type'; // 发放类型

    const string getMinAmount = 'min_amount'; // 最小金额

    const string getMaxAmount = 'max_amount'; // 最大金额

    const string getSendStartDate = 'send_start_date'; // 发放开始时间

    const string getSendEndDate = 'send_end_date'; // 发放结束时间

    const string getUseStartDate = 'use_start_date'; // 使用开始时间

    const string getUseEndDate = 'use_end_date'; // 使用结束时间

    const string getMinGoodsAmount = 'min_goods_amount'; // 最小商品金额

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'typeId', description: '', type: 'integer')]
    private int $typeId;

    #[OA\Property(property: 'typeName', description: '红包类型名称', type: 'string')]
    private string $typeName;

    #[OA\Property(property: 'typeMoney', description: '红包金额', type: 'string')]
    private string $typeMoney;

    #[OA\Property(property: 'sendType', description: '发放类型', type: 'integer')]
    private int $sendType;

    #[OA\Property(property: 'minAmount', description: '最小金额', type: 'string')]
    private string $minAmount;

    #[OA\Property(property: 'maxAmount', description: '最大金额', type: 'string')]
    private string $maxAmount;

    #[OA\Property(property: 'sendStartDate', description: '发放开始时间', type: 'integer')]
    private int $sendStartDate;

    #[OA\Property(property: 'sendEndDate', description: '发放结束时间', type: 'integer')]
    private int $sendEndDate;

    #[OA\Property(property: 'useStartDate', description: '使用开始时间', type: 'integer')]
    private int $useStartDate;

    #[OA\Property(property: 'useEndDate', description: '使用结束时间', type: 'integer')]
    private int $useEndDate;

    #[OA\Property(property: 'minGoodsAmount', description: '最小商品金额', type: 'string')]
    private string $minGoodsAmount;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getTypeId(): int
    {
        return $this->typeId;
    }

    /**
     * 设置
     */
    public function setTypeId(int $typeId): void
    {
        $this->typeId = $typeId;
    }

    /**
     * 获取红包类型名称
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    /**
     * 设置红包类型名称
     */
    public function setTypeName(string $typeName): void
    {
        $this->typeName = $typeName;
    }

    /**
     * 获取红包金额
     */
    public function getTypeMoney(): string
    {
        return $this->typeMoney;
    }

    /**
     * 设置红包金额
     */
    public function setTypeMoney(string $typeMoney): void
    {
        $this->typeMoney = $typeMoney;
    }

    /**
     * 获取发放类型
     */
    public function getSendType(): int
    {
        return $this->sendType;
    }

    /**
     * 设置发放类型
     */
    public function setSendType(int $sendType): void
    {
        $this->sendType = $sendType;
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
     * 获取发放开始时间
     */
    public function getSendStartDate(): int
    {
        return $this->sendStartDate;
    }

    /**
     * 设置发放开始时间
     */
    public function setSendStartDate(int $sendStartDate): void
    {
        $this->sendStartDate = $sendStartDate;
    }

    /**
     * 获取发放结束时间
     */
    public function getSendEndDate(): int
    {
        return $this->sendEndDate;
    }

    /**
     * 设置发放结束时间
     */
    public function setSendEndDate(int $sendEndDate): void
    {
        $this->sendEndDate = $sendEndDate;
    }

    /**
     * 获取使用开始时间
     */
    public function getUseStartDate(): int
    {
        return $this->useStartDate;
    }

    /**
     * 设置使用开始时间
     */
    public function setUseStartDate(int $useStartDate): void
    {
        $this->useStartDate = $useStartDate;
    }

    /**
     * 获取使用结束时间
     */
    public function getUseEndDate(): int
    {
        return $this->useEndDate;
    }

    /**
     * 设置使用结束时间
     */
    public function setUseEndDate(int $useEndDate): void
    {
        $this->useEndDate = $useEndDate;
    }

    /**
     * 获取最小商品金额
     */
    public function getMinGoodsAmount(): string
    {
        return $this->minGoodsAmount;
    }

    /**
     * 设置最小商品金额
     */
    public function setMinGoodsAmount(string $minGoodsAmount): void
    {
        $this->minGoodsAmount = $minGoodsAmount;
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
