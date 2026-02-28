<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsLinkGoodsEntity')]
class GoodsLinkGoodsEntity
{
    use DTOHelper;

    const string getId = 'id'; // ID

    const string getGoodsId = 'goods_id'; // 商品ID

    const string getLinkGoodsId = 'link_goods_id'; // 关联商品ID

    const string getIsDouble = 'is_double'; // 是否双向关联

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'linkGoodsId', description: '关联商品ID', type: 'integer')]
    private int $linkGoodsId;

    #[OA\Property(property: 'isDouble', description: '是否双向关联', type: 'integer')]
    private int $isDouble;

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
     * 获取关联商品ID
     */
    public function getLinkGoodsId(): int
    {
        return $this->linkGoodsId;
    }

    /**
     * 设置关联商品ID
     */
    public function setLinkGoodsId(int $linkGoodsId): void
    {
        $this->linkGoodsId = $linkGoodsId;
    }

    /**
     * 获取是否双向关联
     */
    public function getIsDouble(): int
    {
        return $this->isDouble;
    }

    /**
     * 设置是否双向关联
     */
    public function setIsDouble(int $isDouble): void
    {
        $this->isDouble = $isDouble;
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
