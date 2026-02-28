<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Responses\GoodsVirtualCard;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsVirtualCardResponse')]
class GoodsVirtualCardResponse
{
    use DTOHelper;

    #[OA\Property(property: 'cardId', description: '', type: 'integer')]
    private int $cardId;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'cardSn', description: '卡号', type: 'string')]
    private string $cardSn;

    #[OA\Property(property: 'cardPassword', description: '卡密', type: 'string')]
    private string $cardPassword;

    #[OA\Property(property: 'addDate', description: '添加日期', type: 'integer')]
    private int $addDate;

    #[OA\Property(property: 'endDate', description: '结束日期', type: 'integer')]
    private int $endDate;

    #[OA\Property(property: 'isSaled', description: '是否已售', type: 'integer')]
    private int $isSaled;

    #[OA\Property(property: 'orderSn', description: '订单号', type: 'string')]
    private string $orderSn;

    #[OA\Property(property: 'crc32', description: 'CRC32校验', type: 'string')]
    private string $crc32;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getCardId(): int
    {
        return $this->cardId;
    }

    /**
     * 设置
     */
    public function setCardId(int $cardId): void
    {
        $this->cardId = $cardId;
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
     * 获取卡号
     */
    public function getCardSn(): string
    {
        return $this->cardSn;
    }

    /**
     * 设置卡号
     */
    public function setCardSn(string $cardSn): void
    {
        $this->cardSn = $cardSn;
    }

    /**
     * 获取卡密
     */
    public function getCardPassword(): string
    {
        return $this->cardPassword;
    }

    /**
     * 设置卡密
     */
    public function setCardPassword(string $cardPassword): void
    {
        $this->cardPassword = $cardPassword;
    }

    /**
     * 获取添加日期
     */
    public function getAddDate(): int
    {
        return $this->addDate;
    }

    /**
     * 设置添加日期
     */
    public function setAddDate(int $addDate): void
    {
        $this->addDate = $addDate;
    }

    /**
     * 获取结束日期
     */
    public function getEndDate(): int
    {
        return $this->endDate;
    }

    /**
     * 设置结束日期
     */
    public function setEndDate(int $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * 获取是否已售
     */
    public function getIsSaled(): int
    {
        return $this->isSaled;
    }

    /**
     * 设置是否已售
     */
    public function setIsSaled(int $isSaled): void
    {
        $this->isSaled = $isSaled;
    }

    /**
     * 获取订单号
     */
    public function getOrderSn(): string
    {
        return $this->orderSn;
    }

    /**
     * 设置订单号
     */
    public function setOrderSn(string $orderSn): void
    {
        $this->orderSn = $orderSn;
    }

    /**
     * 获取CRC32校验
     */
    public function getCrc32(): string
    {
        return $this->crc32;
    }

    /**
     * 设置CRC32校验
     */
    public function setCrc32(string $crc32): void
    {
        $this->crc32 = $crc32;
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
