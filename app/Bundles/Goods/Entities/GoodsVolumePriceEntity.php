<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsVolumePriceEntity')]
class GoodsVolumePriceEntity
{
    use DTOHelper;

    const string getId = 'id'; // ID

    const string getPriceType = 'price_type'; // 价格类型

    const string getGoodsId = 'goods_id'; // 商品ID

    const string getVolumeNumber = 'volume_number'; // 数量

    const string getVolumePrice = 'volume_price'; // 批发价格

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'priceType', description: '价格类型', type: 'integer')]
    private int $priceType;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'volumeNumber', description: '数量', type: 'integer')]
    private int $volumeNumber;

    #[OA\Property(property: 'volumePrice', description: '批发价格', type: 'string')]
    private string $volumePrice;

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
     * 获取价格类型
     */
    public function getPriceType(): int
    {
        return $this->priceType;
    }

    /**
     * 设置价格类型
     */
    public function setPriceType(int $priceType): void
    {
        $this->priceType = $priceType;
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
     * 获取数量
     */
    public function getVolumeNumber(): int
    {
        return $this->volumeNumber;
    }

    /**
     * 设置数量
     */
    public function setVolumeNumber(int $volumeNumber): void
    {
        $this->volumeNumber = $volumeNumber;
    }

    /**
     * 获取批发价格
     */
    public function getVolumePrice(): string
    {
        return $this->volumePrice;
    }

    /**
     * 设置批发价格
     */
    public function setVolumePrice(string $volumePrice): void
    {
        $this->volumePrice = $volumePrice;
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
