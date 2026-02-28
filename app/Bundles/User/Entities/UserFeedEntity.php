<?php

declare(strict_types=1);

namespace App\Bundles\User\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserFeedEntity')]
class UserFeedEntity
{
    use DTOHelper;

    const string getFeedId = 'feed_id';

    const string getUserId = 'user_id'; // 用户ID

    const string getValueId = 'value_id'; // 值ID

    const string getGoodsId = 'goods_id'; // 商品ID

    const string getFeedType = 'feed_type'; // 动态类型

    const string getIsFeed = 'is_feed'; // 是否动态

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'feedId', description: '', type: 'integer')]
    private int $feedId;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'valueId', description: '值ID', type: 'integer')]
    private int $valueId;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'feedType', description: '动态类型', type: 'integer')]
    private int $feedType;

    #[OA\Property(property: 'isFeed', description: '是否动态', type: 'integer')]
    private int $isFeed;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getFeedId(): int
    {
        return $this->feedId;
    }

    /**
     * 设置
     */
    public function setFeedId(int $feedId): void
    {
        $this->feedId = $feedId;
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
     * 获取值ID
     */
    public function getValueId(): int
    {
        return $this->valueId;
    }

    /**
     * 设置值ID
     */
    public function setValueId(int $valueId): void
    {
        $this->valueId = $valueId;
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
     * 获取动态类型
     */
    public function getFeedType(): int
    {
        return $this->feedType;
    }

    /**
     * 设置动态类型
     */
    public function setFeedType(int $feedType): void
    {
        $this->feedType = $feedType;
    }

    /**
     * 获取是否动态
     */
    public function getIsFeed(): int
    {
        return $this->isFeed;
    }

    /**
     * 设置是否动态
     */
    public function setIsFeed(int $isFeed): void
    {
        $this->isFeed = $isFeed;
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
