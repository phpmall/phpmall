<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsBrandEntity')]
class GoodsBrandEntity
{
    use DTOHelper;

    const string getBrandId = 'brand_id';

    const string getBrandName = 'brand_name'; // 品牌名称

    const string getBrandLogo = 'brand_logo'; // 品牌Logo

    const string getBrandDesc = 'brand_desc'; // 品牌描述

    const string getSiteUrl = 'site_url'; // 品牌网址

    const string getSortOrder = 'sort_order'; // 排序顺序

    const string getIsShow = 'is_show'; // 是否显示

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'brandId', description: '', type: 'integer')]
    private int $brandId;

    #[OA\Property(property: 'brandName', description: '品牌名称', type: 'string')]
    private string $brandName;

    #[OA\Property(property: 'brandLogo', description: '品牌Logo', type: 'string')]
    private string $brandLogo;

    #[OA\Property(property: 'brandDesc', description: '品牌描述', type: 'string')]
    private string $brandDesc;

    #[OA\Property(property: 'siteUrl', description: '品牌网址', type: 'string')]
    private string $siteUrl;

    #[OA\Property(property: 'sortOrder', description: '排序顺序', type: 'integer')]
    private int $sortOrder;

    #[OA\Property(property: 'isShow', description: '是否显示', type: 'integer')]
    private int $isShow;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getBrandId(): int
    {
        return $this->brandId;
    }

    /**
     * 设置
     */
    public function setBrandId(int $brandId): void
    {
        $this->brandId = $brandId;
    }

    /**
     * 获取品牌名称
     */
    public function getBrandName(): string
    {
        return $this->brandName;
    }

    /**
     * 设置品牌名称
     */
    public function setBrandName(string $brandName): void
    {
        $this->brandName = $brandName;
    }

    /**
     * 获取品牌Logo
     */
    public function getBrandLogo(): string
    {
        return $this->brandLogo;
    }

    /**
     * 设置品牌Logo
     */
    public function setBrandLogo(string $brandLogo): void
    {
        $this->brandLogo = $brandLogo;
    }

    /**
     * 获取品牌描述
     */
    public function getBrandDesc(): string
    {
        return $this->brandDesc;
    }

    /**
     * 设置品牌描述
     */
    public function setBrandDesc(string $brandDesc): void
    {
        $this->brandDesc = $brandDesc;
    }

    /**
     * 获取品牌网址
     */
    public function getSiteUrl(): string
    {
        return $this->siteUrl;
    }

    /**
     * 设置品牌网址
     */
    public function setSiteUrl(string $siteUrl): void
    {
        $this->siteUrl = $siteUrl;
    }

    /**
     * 获取排序顺序
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * 设置排序顺序
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * 获取是否显示
     */
    public function getIsShow(): int
    {
        return $this->isShow;
    }

    /**
     * 设置是否显示
     */
    public function setIsShow(int $isShow): void
    {
        $this->isShow = $isShow;
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
