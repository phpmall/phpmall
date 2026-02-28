<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdPositionEntity')]
class AdPositionEntity
{
    use DTOHelper;

    const string getPositionId = 'position_id';

    const string getPositionName = 'position_name'; // 广告位名称

    const string getAdWidth = 'ad_width'; // 广告宽度

    const string getAdHeight = 'ad_height'; // 广告高度

    const string getPositionDesc = 'position_desc'; // 广告位描述

    const string getPositionStyle = 'position_style'; // 广告位样式

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'positionId', description: '', type: 'integer')]
    private int $positionId;

    #[OA\Property(property: 'positionName', description: '广告位名称', type: 'string')]
    private string $positionName;

    #[OA\Property(property: 'adWidth', description: '广告宽度', type: 'integer')]
    private int $adWidth;

    #[OA\Property(property: 'adHeight', description: '广告高度', type: 'integer')]
    private int $adHeight;

    #[OA\Property(property: 'positionDesc', description: '广告位描述', type: 'string')]
    private string $positionDesc;

    #[OA\Property(property: 'positionStyle', description: '广告位样式', type: 'string')]
    private string $positionStyle;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getPositionId(): int
    {
        return $this->positionId;
    }

    /**
     * 设置
     */
    public function setPositionId(int $positionId): void
    {
        $this->positionId = $positionId;
    }

    /**
     * 获取广告位名称
     */
    public function getPositionName(): string
    {
        return $this->positionName;
    }

    /**
     * 设置广告位名称
     */
    public function setPositionName(string $positionName): void
    {
        $this->positionName = $positionName;
    }

    /**
     * 获取广告宽度
     */
    public function getAdWidth(): int
    {
        return $this->adWidth;
    }

    /**
     * 设置广告宽度
     */
    public function setAdWidth(int $adWidth): void
    {
        $this->adWidth = $adWidth;
    }

    /**
     * 获取广告高度
     */
    public function getAdHeight(): int
    {
        return $this->adHeight;
    }

    /**
     * 设置广告高度
     */
    public function setAdHeight(int $adHeight): void
    {
        $this->adHeight = $adHeight;
    }

    /**
     * 获取广告位描述
     */
    public function getPositionDesc(): string
    {
        return $this->positionDesc;
    }

    /**
     * 设置广告位描述
     */
    public function setPositionDesc(string $positionDesc): void
    {
        $this->positionDesc = $positionDesc;
    }

    /**
     * 获取广告位样式
     */
    public function getPositionStyle(): string
    {
        return $this->positionStyle;
    }

    /**
     * 设置广告位样式
     */
    public function setPositionStyle(string $positionStyle): void
    {
        $this->positionStyle = $positionStyle;
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
