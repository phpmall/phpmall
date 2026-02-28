<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Responses\ActivityExchange;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ActivityExchangeResponse')]
class ActivityExchangeResponse
{
    use DTOHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'exchangeIntegral', description: '兑换积分', type: 'integer')]
    private int $exchangeIntegral;

    #[OA\Property(property: 'isExchange', description: '是否可兑换', type: 'integer')]
    private int $isExchange;

    #[OA\Property(property: 'isHot', description: '是否热门', type: 'integer')]
    private int $isHot;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     * 获取兑换积分
     */
    public function getExchangeIntegral(): int
    {
        return $this->exchangeIntegral;
    }

    /**
     * 设置兑换积分
     */
    public function setExchangeIntegral(int $exchangeIntegral): void
    {
        $this->exchangeIntegral = $exchangeIntegral;
    }

    /**
     * 获取是否可兑换
     */
    public function getIsExchange(): int
    {
        return $this->isExchange;
    }

    /**
     * 设置是否可兑换
     */
    public function setIsExchange(int $isExchange): void
    {
        $this->isExchange = $isExchange;
    }

    /**
     * 获取是否热门
     */
    public function getIsHot(): int
    {
        return $this->isHot;
    }

    /**
     * 设置是否热门
     */
    public function setIsHot(int $isHot): void
    {
        $this->isHot = $isHot;
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
