<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsMemberPriceEntity')]
class GoodsMemberPriceEntity
{
    use DTOHelper;

    const string getPriceId = 'price_id';

    const string getGoodsId = 'goods_id'; // 商品ID

    const string getUserRank = 'user_rank'; // 用户等级

    const string getUserPrice = 'user_price'; // 会员价格

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'priceId', description: '', type: 'integer')]
    private int $priceId;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'userRank', description: '用户等级', type: 'integer')]
    private int $userRank;

    #[OA\Property(property: 'userPrice', description: '会员价格', type: 'string')]
    private string $userPrice;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getPriceId(): int
    {
        return $this->priceId;
    }

    /**
     * 设置
     */
    public function setPriceId(int $priceId): void
    {
        $this->priceId = $priceId;
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
     * 获取用户等级
     */
    public function getUserRank(): int
    {
        return $this->userRank;
    }

    /**
     * 设置用户等级
     */
    public function setUserRank(int $userRank): void
    {
        $this->userRank = $userRank;
    }

    /**
     * 获取会员价格
     */
    public function getUserPrice(): string
    {
        return $this->userPrice;
    }

    /**
     * 设置会员价格
     */
    public function setUserPrice(string $userPrice): void
    {
        $this->userPrice = $userPrice;
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
