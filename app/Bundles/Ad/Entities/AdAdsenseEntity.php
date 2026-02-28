<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdAdsenseEntity')]
class AdAdsenseEntity
{
    use DTOHelper;

    const string getId = 'id'; // ID

    const string getFromAd = 'from_ad'; // 广告ID

    const string getReferer = 'referer'; // 来源页面

    const string getClicks = 'clicks'; // 点击次数

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'fromAd', description: '广告ID', type: 'integer')]
    private int $fromAd;

    #[OA\Property(property: 'referer', description: '来源页面', type: 'string')]
    private string $referer;

    #[OA\Property(property: 'clicks', description: '点击次数', type: 'integer')]
    private int $clicks;

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
     * 获取广告ID
     */
    public function getFromAd(): int
    {
        return $this->fromAd;
    }

    /**
     * 设置广告ID
     */
    public function setFromAd(int $fromAd): void
    {
        $this->fromAd = $fromAd;
    }

    /**
     * 获取来源页面
     */
    public function getReferer(): string
    {
        return $this->referer;
    }

    /**
     * 设置来源页面
     */
    public function setReferer(string $referer): void
    {
        $this->referer = $referer;
    }

    /**
     * 获取点击次数
     */
    public function getClicks(): int
    {
        return $this->clicks;
    }

    /**
     * 设置点击次数
     */
    public function setClicks(int $clicks): void
    {
        $this->clicks = $clicks;
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
