<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ActivityWholesaleEntity')]
class ActivityWholesaleEntity
{
    use DTOHelper;

    const string getActId = 'act_id';

    const string getGoodsId = 'goods_id'; // 商品ID

    const string getGoodsName = 'goods_name'; // 商品名称

    const string getRankIds = 'rank_ids'; // 等级ID

    const string getPrices = 'prices'; // 价格

    const string getEnabled = 'enabled'; // 是否启用

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'actId', description: '', type: 'integer')]
    private int $actId;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'goodsName', description: '商品名称', type: 'string')]
    private string $goodsName;

    #[OA\Property(property: 'rankIds', description: '等级ID', type: 'string')]
    private string $rankIds;

    #[OA\Property(property: 'prices', description: '价格', type: 'string')]
    private string $prices;

    #[OA\Property(property: 'enabled', description: '是否启用', type: 'integer')]
    private int $enabled;

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
     * 获取等级ID
     */
    public function getRankIds(): string
    {
        return $this->rankIds;
    }

    /**
     * 设置等级ID
     */
    public function setRankIds(string $rankIds): void
    {
        $this->rankIds = $rankIds;
    }

    /**
     * 获取价格
     */
    public function getPrices(): string
    {
        return $this->prices;
    }

    /**
     * 设置价格
     */
    public function setPrices(string $prices): void
    {
        $this->prices = $prices;
    }

    /**
     * 获取是否启用
     */
    public function getEnabled(): int
    {
        return $this->enabled;
    }

    /**
     * 设置是否启用
     */
    public function setEnabled(int $enabled): void
    {
        $this->enabled = $enabled;
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
