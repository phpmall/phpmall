<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopPackEntity')]
class ShopPackEntity
{
    use DTOHelper;

    const string getPackId = 'pack_id';

    const string getPackName = 'pack_name'; // 包装名称

    const string getPackImg = 'pack_img'; // 包装图片

    const string getPackFee = 'pack_fee'; // 包装费用

    const string getFreeMoney = 'free_money'; // 免费额度

    const string getPackDesc = 'pack_desc'; // 包装描述

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'packId', description: '', type: 'integer')]
    private int $packId;

    #[OA\Property(property: 'packName', description: '包装名称', type: 'string')]
    private string $packName;

    #[OA\Property(property: 'packImg', description: '包装图片', type: 'string')]
    private string $packImg;

    #[OA\Property(property: 'packFee', description: '包装费用', type: 'string')]
    private string $packFee;

    #[OA\Property(property: 'freeMoney', description: '免费额度', type: 'integer')]
    private int $freeMoney;

    #[OA\Property(property: 'packDesc', description: '包装描述', type: 'string')]
    private string $packDesc;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getPackId(): int
    {
        return $this->packId;
    }

    /**
     * 设置
     */
    public function setPackId(int $packId): void
    {
        $this->packId = $packId;
    }

    /**
     * 获取包装名称
     */
    public function getPackName(): string
    {
        return $this->packName;
    }

    /**
     * 设置包装名称
     */
    public function setPackName(string $packName): void
    {
        $this->packName = $packName;
    }

    /**
     * 获取包装图片
     */
    public function getPackImg(): string
    {
        return $this->packImg;
    }

    /**
     * 设置包装图片
     */
    public function setPackImg(string $packImg): void
    {
        $this->packImg = $packImg;
    }

    /**
     * 获取包装费用
     */
    public function getPackFee(): string
    {
        return $this->packFee;
    }

    /**
     * 设置包装费用
     */
    public function setPackFee(string $packFee): void
    {
        $this->packFee = $packFee;
    }

    /**
     * 获取免费额度
     */
    public function getFreeMoney(): int
    {
        return $this->freeMoney;
    }

    /**
     * 设置免费额度
     */
    public function setFreeMoney(int $freeMoney): void
    {
        $this->freeMoney = $freeMoney;
    }

    /**
     * 获取包装描述
     */
    public function getPackDesc(): string
    {
        return $this->packDesc;
    }

    /**
     * 设置包装描述
     */
    public function setPackDesc(string $packDesc): void
    {
        $this->packDesc = $packDesc;
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
