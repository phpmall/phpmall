<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopCardEntity')]
class ShopCardEntity
{
    use DTOHelper;

    const string getCardId = 'card_id';

    const string getCardName = 'card_name'; // 贺卡名称

    const string getCardImg = 'card_img'; // 贺卡图片

    const string getCardFee = 'card_fee'; // 贺卡费用

    const string getFreeMoney = 'free_money'; // 免费额度

    const string getCardDesc = 'card_desc'; // 贺卡描述

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'cardId', description: '', type: 'integer')]
    private int $cardId;

    #[OA\Property(property: 'cardName', description: '贺卡名称', type: 'string')]
    private string $cardName;

    #[OA\Property(property: 'cardImg', description: '贺卡图片', type: 'string')]
    private string $cardImg;

    #[OA\Property(property: 'cardFee', description: '贺卡费用', type: 'string')]
    private string $cardFee;

    #[OA\Property(property: 'freeMoney', description: '免费额度', type: 'string')]
    private string $freeMoney;

    #[OA\Property(property: 'cardDesc', description: '贺卡描述', type: 'string')]
    private string $cardDesc;

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
     * 获取贺卡名称
     */
    public function getCardName(): string
    {
        return $this->cardName;
    }

    /**
     * 设置贺卡名称
     */
    public function setCardName(string $cardName): void
    {
        $this->cardName = $cardName;
    }

    /**
     * 获取贺卡图片
     */
    public function getCardImg(): string
    {
        return $this->cardImg;
    }

    /**
     * 设置贺卡图片
     */
    public function setCardImg(string $cardImg): void
    {
        $this->cardImg = $cardImg;
    }

    /**
     * 获取贺卡费用
     */
    public function getCardFee(): string
    {
        return $this->cardFee;
    }

    /**
     * 设置贺卡费用
     */
    public function setCardFee(string $cardFee): void
    {
        $this->cardFee = $cardFee;
    }

    /**
     * 获取免费额度
     */
    public function getFreeMoney(): string
    {
        return $this->freeMoney;
    }

    /**
     * 设置免费额度
     */
    public function setFreeMoney(string $freeMoney): void
    {
        $this->freeMoney = $freeMoney;
    }

    /**
     * 获取贺卡描述
     */
    public function getCardDesc(): string
    {
        return $this->cardDesc;
    }

    /**
     * 设置贺卡描述
     */
    public function setCardDesc(string $cardDesc): void
    {
        $this->cardDesc = $cardDesc;
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
