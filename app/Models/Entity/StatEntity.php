<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Focite\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'StatEntity')]
class StatEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'access_time', description: '请求日期', type: 'string')]
    protected string $accessTime;

    #[OA\Property(property: 'visit_times', description: '请求次数', type: 'integer')]
    protected int $visitTimes;

    #[OA\Property(property: 'ip_address', description: 'IP地址', type: 'string')]
    protected string $ipAddress;

    #[OA\Property(property: 'system', description: '操作系统', type: 'string')]
    protected string $system;

    #[OA\Property(property: 'browser', description: '浏览器', type: 'string')]
    protected string $browser;

    #[OA\Property(property: 'language', description: '语言', type: 'string')]
    protected string $language;

    #[OA\Property(property: 'area', description: '地区', type: 'string')]
    protected string $area;

    #[OA\Property(property: 'referer_domain', description: '来源域名', type: 'string')]
    protected string $refererDomain;

    #[OA\Property(property: 'referer_path', description: '来源地址', type: 'string')]
    protected string $refererPath;

    #[OA\Property(property: 'access_url', description: '请求url地址', type: 'string')]
    protected string $accessUrl;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取请求日期
     */
    public function getAccessTime(): string
    {
        return $this->accessTime;
    }

    /**
     * 设置请求日期
     */
    public function setAccessTime(string $accessTime): void
    {
        $this->accessTime = $accessTime;
    }

    /**
     * 获取请求次数
     */
    public function getVisitTimes(): int
    {
        return $this->visitTimes;
    }

    /**
     * 设置请求次数
     */
    public function setVisitTimes(int $visitTimes): void
    {
        $this->visitTimes = $visitTimes;
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
     * 获取来源地址
     */
    public function getRefererPath(): string
    {
        return $this->refererPath;
    }

    /**
     * 设置来源地址
     */
    public function setRefererPath(string $refererPath): void
    {
        $this->refererPath = $refererPath;
    }

    /**
     * 获取请求url地址
     */
    public function getAccessUrl(): string
    {
        return $this->accessUrl;
    }

    /**
     * 设置请求url地址
     */
    public function setAccessUrl(string $accessUrl): void
    {
        $this->accessUrl = $accessUrl;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
