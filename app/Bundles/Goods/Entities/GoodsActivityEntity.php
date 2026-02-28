<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsActivityEntity')]
class GoodsActivityEntity
{
    use DTOHelper;

    const string getActId = 'act_id';

    const string getActName = 'act_name'; // 活动名称

    const string getActDesc = 'act_desc'; // 活动描述

    const string getActType = 'act_type'; // 活动类型

    const string getGoodsId = 'goods_id'; // 商品ID

    const string getProductId = 'product_id'; // 货品ID

    const string getGoodsName = 'goods_name'; // 商品名称

    const string getStartTime = 'start_time'; // 开始时间

    const string getEndTime = 'end_time'; // 结束时间

    const string getIsFinished = 'is_finished'; // 是否结束

    const string getExtInfo = 'ext_info'; // 扩展信息

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'actId', description: '', type: 'integer')]
    private int $actId;

    #[OA\Property(property: 'actName', description: '活动名称', type: 'string')]
    private string $actName;

    #[OA\Property(property: 'actDesc', description: '活动描述', type: 'string')]
    private string $actDesc;

    #[OA\Property(property: 'actType', description: '活动类型', type: 'integer')]
    private int $actType;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'productId', description: '货品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'goodsName', description: '商品名称', type: 'string')]
    private string $goodsName;

    #[OA\Property(property: 'startTime', description: '开始时间', type: 'integer')]
    private int $startTime;

    #[OA\Property(property: 'endTime', description: '结束时间', type: 'integer')]
    private int $endTime;

    #[OA\Property(property: 'isFinished', description: '是否结束', type: 'integer')]
    private int $isFinished;

    #[OA\Property(property: 'extInfo', description: '扩展信息', type: 'string')]
    private string $extInfo;

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
     * 获取活动描述
     */
    public function getActDesc(): string
    {
        return $this->actDesc;
    }

    /**
     * 设置活动描述
     */
    public function setActDesc(string $actDesc): void
    {
        $this->actDesc = $actDesc;
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
     * 获取商品ID
     */
    public function getGoodsId(): int
    {
        return $this->goodsId;
    }

    /**
     * 设置商品ID
     */
    public function setGoodsId(int $goodsId): void
    {
        $this->goodsId = $goodsId;
    }

    /**
     * 获取货品ID
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * 设置货品ID
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * 获取商品名称
     */
    public function getGoodsName(): string
    {
        return $this->goodsName;
    }

    /**
     * 设置商品名称
     */
    public function setGoodsName(string $goodsName): void
    {
        $this->goodsName = $goodsName;
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
     * 获取是否结束
     */
    public function getIsFinished(): int
    {
        return $this->isFinished;
    }

    /**
     * 设置是否结束
     */
    public function setIsFinished(int $isFinished): void
    {
        $this->isFinished = $isFinished;
    }

    /**
     * 获取扩展信息
     */
    public function getExtInfo(): string
    {
        return $this->extInfo;
    }

    /**
     * 设置扩展信息
     */
    public function setExtInfo(string $extInfo): void
    {
        $this->extInfo = $extInfo;
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
