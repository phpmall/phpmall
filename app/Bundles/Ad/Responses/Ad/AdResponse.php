<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Responses\Ad;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdResponse')]
class AdResponse
{
    use DTOHelper;

    #[OA\Property(property: 'adId', description: '', type: 'integer')]
    private int $adId;

    #[OA\Property(property: 'positionId', description: '广告位置ID', type: 'integer')]
    private int $positionId;

    #[OA\Property(property: 'mediaType', description: '媒体类型', type: 'integer')]
    private int $mediaType;

    #[OA\Property(property: 'adName', description: '广告名称', type: 'string')]
    private string $adName;

    #[OA\Property(property: 'adLink', description: '广告链接', type: 'string')]
    private string $adLink;

    #[OA\Property(property: 'adCode', description: '广告代码', type: 'string')]
    private string $adCode;

    #[OA\Property(property: 'startTime', description: '开始时间', type: 'integer')]
    private int $startTime;

    #[OA\Property(property: 'endTime', description: '结束时间', type: 'integer')]
    private int $endTime;

    #[OA\Property(property: 'linkMan', description: '联系人', type: 'string')]
    private string $linkMan;

    #[OA\Property(property: 'linkEmail', description: '联系邮箱', type: 'string')]
    private string $linkEmail;

    #[OA\Property(property: 'linkPhone', description: '联系电话', type: 'string')]
    private string $linkPhone;

    #[OA\Property(property: 'clickCount', description: '点击次数', type: 'integer')]
    private int $clickCount;

    #[OA\Property(property: 'enabled', description: '是否启用', type: 'integer')]
    private int $enabled;

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
     * 获取广告位置ID
     */
    public function getPositionId(): int
    {
        return $this->positionId;
    }

    /**
     * 设置广告位置ID
     */
    public function setPositionId(int $positionId): void
    {
        $this->positionId = $positionId;
    }

    /**
     * 获取媒体类型
     */
    public function getMediaType(): int
    {
        return $this->mediaType;
    }

    /**
     * 设置媒体类型
     */
    public function setMediaType(int $mediaType): void
    {
        $this->mediaType = $mediaType;
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
     * 获取广告链接
     */
    public function getAdLink(): string
    {
        return $this->adLink;
    }

    /**
     * 设置广告链接
     */
    public function setAdLink(string $adLink): void
    {
        $this->adLink = $adLink;
    }

    /**
     * 获取广告代码
     */
    public function getAdCode(): string
    {
        return $this->adCode;
    }

    /**
     * 设置广告代码
     */
    public function setAdCode(string $adCode): void
    {
        $this->adCode = $adCode;
    }

    /**
     * 获取开始时间
     */
    public function getStartTime(): int
    {
        return $this->startTime;
    }

    /**
     * 设置开始时间
     */
    public function setStartTime(int $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取结束时间
     */
    public function getEndTime(): int
    {
        return $this->endTime;
    }

    /**
     * 设置结束时间
     */
    public function setEndTime(int $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * 获取联系人
     */
    public function getLinkMan(): string
    {
        return $this->linkMan;
    }

    /**
     * 设置联系人
     */
    public function setLinkMan(string $linkMan): void
    {
        $this->linkMan = $linkMan;
    }

    /**
     * 获取联系邮箱
     */
    public function getLinkEmail(): string
    {
        return $this->linkEmail;
    }

    /**
     * 设置联系邮箱
     */
    public function setLinkEmail(string $linkEmail): void
    {
        $this->linkEmail = $linkEmail;
    }

    /**
     * 获取联系电话
     */
    public function getLinkPhone(): string
    {
        return $this->linkPhone;
    }

    /**
     * 设置联系电话
     */
    public function setLinkPhone(string $linkPhone): void
    {
        $this->linkPhone = $linkPhone;
    }

    /**
     * 获取点击次数
     */
    public function getClickCount(): int
    {
        return $this->clickCount;
    }

    /**
     * 设置点击次数
     */
    public function setClickCount(int $clickCount): void
    {
        $this->clickCount = $clickCount;
    }

    /**
     * 获取是否启用
     */
    public function getEnabled(): int
    {
        return $this->enabled;
    }

    /**
     * 设置是否启用
     */
    public function setEnabled(int $enabled): void
    {
        $this->enabled = $enabled;
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
