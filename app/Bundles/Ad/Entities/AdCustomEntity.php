<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdCustomEntity')]
class AdCustomEntity
{
    use DTOHelper;

    const string getAdId = 'ad_id';

    const string getAdType = 'ad_type'; // 广告类型

    const string getAdName = 'ad_name'; // 广告名称

    const string getAddTime = 'add_time'; // 添加时间

    const string getContent = 'content'; // 广告内容

    const string getUrl = 'url'; // 广告链接

    const string getAdStatus = 'ad_status'; // 广告状态

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'adId', description: '', type: 'integer')]
    private int $adId;

    #[OA\Property(property: 'adType', description: '广告类型', type: 'integer')]
    private int $adType;

    #[OA\Property(property: 'adName', description: '广告名称', type: 'string')]
    private string $adName;

    #[OA\Property(property: 'addTime', description: '添加时间', type: 'integer')]
    private int $addTime;

    #[OA\Property(property: 'content', description: '广告内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'url', description: '广告链接', type: 'string')]
    private string $url;

    #[OA\Property(property: 'adStatus', description: '广告状态', type: 'integer')]
    private int $adStatus;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getAdId(): int
    {
        return $this->adId;
    }

    /**
     * 设置
     */
    public function setAdId(int $adId): void
    {
        $this->adId = $adId;
    }

    /**
     * 获取广告类型
     */
    public function getAdType(): int
    {
        return $this->adType;
    }

    /**
     * 设置广告类型
     */
    public function setAdType(int $adType): void
    {
        $this->adType = $adType;
    }

    /**
     * 获取广告名称
     */
    public function getAdName(): string
    {
        return $this->adName;
    }

    /**
     * 设置广告名称
     */
    public function setAdName(string $adName): void
    {
        $this->adName = $adName;
    }

    /**
     * 获取添加时间
     */
    public function getAddTime(): int
    {
        return $this->addTime;
    }

    /**
     * 设置添加时间
     */
    public function setAddTime(int $addTime): void
    {
        $this->addTime = $addTime;
    }

    /**
     * 获取广告内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置广告内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取广告链接
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * 设置广告链接
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * 获取广告状态
     */
    public function getAdStatus(): int
    {
        return $this->adStatus;
    }

    /**
     * 设置广告状态
     */
    public function setAdStatus(int $adStatus): void
    {
        $this->adStatus = $adStatus;
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
