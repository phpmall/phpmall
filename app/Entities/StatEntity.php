<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'StatEntity')]
class StatEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'access_time', description: '请求日期', type: 'string')]
    protected string $access_time;

    #[OA\Property(property: 'visit_times', description: '请求次数', type: 'integer')]
    protected int $visit_times;

    #[OA\Property(property: 'ip_address', description: 'IP地址', type: 'string')]
    protected string $ip_address;

    #[OA\Property(property: 'system', description: '操作系统', type: 'string')]
    protected string $system;

    #[OA\Property(property: 'browser', description: '浏览器', type: 'string')]
    protected string $browser;

    #[OA\Property(property: 'language', description: '语言', type: 'string')]
    protected string $language;

    #[OA\Property(property: 'area', description: '地区', type: 'string')]
    protected string $area;

    #[OA\Property(property: 'referer_domain', description: '来源域名', type: 'string')]
    protected string $referer_domain;

    #[OA\Property(property: 'referer_path', description: '来源地址', type: 'string')]
    protected string $referer_path;

    #[OA\Property(property: 'access_url', description: '请求url地址', type: 'string')]
    protected string $access_url;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

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
        return $this->access_time;
    }

    /**
     * 设置请求日期
     */
    public function setAccessTime(string $access_time): void
    {
        $this->access_time = $access_time;
    }

    /**
     * 获取请求次数
     */
    public function getVisitTimes(): int
    {
        return $this->visit_times;
    }

    /**
     * 设置请求次数
     */
    public function setVisitTimes(int $visit_times): void
    {
        $this->visit_times = $visit_times;
    }

    /**
     * 获取IP地址
     */
    public function getIpAddress(): string
    {
        return $this->ip_address;
    }

    /**
     * 设置IP地址
     */
    public function setIpAddress(string $ip_address): void
    {
        $this->ip_address = $ip_address;
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
        return $this->referer_domain;
    }

    /**
     * 设置来源域名
     */
    public function setRefererDomain(string $referer_domain): void
    {
        $this->referer_domain = $referer_domain;
    }

    /**
     * 获取来源地址
     */
    public function getRefererPath(): string
    {
        return $this->referer_path;
    }

    /**
     * 设置来源地址
     */
    public function setRefererPath(string $referer_path): void
    {
        $this->referer_path = $referer_path;
    }

    /**
     * 获取请求url地址
     */
    public function getAccessUrl(): string
    {
        return $this->access_url;
    }

    /**
     * 设置请求url地址
     */
    public function setAccessUrl(string $access_url): void
    {
        $this->access_url = $access_url;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}
