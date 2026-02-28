<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Responses\ShopStats;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopStatsResponse')]
class ShopStatsResponse
{
    use DTOHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'accessTime', description: '访问时间', type: 'integer')]
    private int $accessTime;

    #[OA\Property(property: 'ipAddress', description: 'IP地址', type: 'string')]
    private string $ipAddress;

    #[OA\Property(property: 'visitTimes', description: '访问次数', type: 'integer')]
    private int $visitTimes;

    #[OA\Property(property: 'browser', description: '浏览器', type: 'string')]
    private string $browser;

    #[OA\Property(property: 'system', description: '操作系统', type: 'string')]
    private string $system;

    #[OA\Property(property: 'language', description: '语言', type: 'string')]
    private string $language;

    #[OA\Property(property: 'area', description: '地区', type: 'string')]
    private string $area;

    #[OA\Property(property: 'refererDomain', description: '来源域名', type: 'string')]
    private string $refererDomain;

    #[OA\Property(property: 'refererPath', description: '来源路径', type: 'string')]
    private string $refererPath;

    #[OA\Property(property: 'accessUrl', description: '访问URL', type: 'string')]
    private string $accessUrl;

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
     * 获取访问时间
     */
    public function getAccessTime(): int
    {
        return $this->accessTime;
    }

    /**
     * 设置访问时间
     */
    public function setAccessTime(int $accessTime): void
    {
        $this->accessTime = $accessTime;
    }

    /**
     * 获取IP地址
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * 设置IP地址
     */
    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * 获取访问次数
     */
    public function getVisitTimes(): int
    {
        return $this->visitTimes;
    }

    /**
     * 设置访问次数
     */
    public function setVisitTimes(int $visitTimes): void
    {
        $this->visitTimes = $visitTimes;
    }

    /**
     * 获取浏览器
     */
    public function getBrowser(): string
    {
        return $this->browser;
    }

    /**
     * 设置浏览器
     */
    public function setBrowser(string $browser): void
    {
        $this->browser = $browser;
    }

    /**
     * 获取操作系统
     */
    public function getSystem(): string
    {
        return $this->system;
    }

    /**
     * 设置操作系统
     */
    public function setSystem(string $system): void
    {
        $this->system = $system;
    }

    /**
     * 获取语言
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * 设置语言
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * 获取地区
     */
    public function getArea(): string
    {
        return $this->area;
    }

    /**
     * 设置地区
     */
    public function setArea(string $area): void
    {
        $this->area = $area;
    }

    /**
     * 获取来源域名
     */
    public function getRefererDomain(): string
    {
        return $this->refererDomain;
    }

    /**
     * 设置来源域名
     */
    public function setRefererDomain(string $refererDomain): void
    {
        $this->refererDomain = $refererDomain;
    }

    /**
     * 获取来源路径
     */
    public function getRefererPath(): string
    {
        return $this->refererPath;
    }

    /**
     * 设置来源路径
     */
    public function setRefererPath(string $refererPath): void
    {
        $this->refererPath = $refererPath;
    }

    /**
     * 获取访问URL
     */
    public function getAccessUrl(): string
    {
        return $this->accessUrl;
    }

    /**
     * 设置访问URL
     */
    public function setAccessUrl(string $accessUrl): void
    {
        $this->accessUrl = $accessUrl;
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
