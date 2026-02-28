<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Responses\ShopCron;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopCronResponse')]
class ShopCronResponse
{
    use DTOHelper;

    #[OA\Property(property: 'cronId', description: '', type: 'integer')]
    private int $cronId;

    #[OA\Property(property: 'cronCode', description: '计划任务代码', type: 'string')]
    private string $cronCode;

    #[OA\Property(property: 'cronName', description: '计划任务名称', type: 'string')]
    private string $cronName;

    #[OA\Property(property: 'cronDesc', description: '计划任务描述', type: 'string')]
    private string $cronDesc;

    #[OA\Property(property: 'cronOrder', description: '排序', type: 'integer')]
    private int $cronOrder;

    #[OA\Property(property: 'cronConfig', description: '计划任务配置', type: 'string')]
    private string $cronConfig;

    #[OA\Property(property: 'thistime', description: '本次执行时间', type: 'integer')]
    private int $thistime;

    #[OA\Property(property: 'nextime', description: '下次执行时间', type: 'integer')]
    private int $nextime;

    #[OA\Property(property: 'day', description: '日', type: 'integer')]
    private int $day;

    #[OA\Property(property: 'week', description: '周', type: 'string')]
    private string $week;

    #[OA\Property(property: 'hour', description: '时', type: 'string')]
    private string $hour;

    #[OA\Property(property: 'minute', description: '分', type: 'string')]
    private string $minute;

    #[OA\Property(property: 'enable', description: '是否启用', type: 'integer')]
    private int $enable;

    #[OA\Property(property: 'runOnce', description: '是否只运行一次', type: 'integer')]
    private int $runOnce;

    #[OA\Property(property: 'allowIp', description: '允许的IP', type: 'string')]
    private string $allowIp;

    #[OA\Property(property: 'alowFiles', description: '允许的文件', type: 'string')]
    private string $alowFiles;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getCronId(): int
    {
        return $this->cronId;
    }

    /**
     * 设置
     */
    public function setCronId(int $cronId): void
    {
        $this->cronId = $cronId;
    }

    /**
     * 获取计划任务代码
     */
    public function getCronCode(): string
    {
        return $this->cronCode;
    }

    /**
     * 设置计划任务代码
     */
    public function setCronCode(string $cronCode): void
    {
        $this->cronCode = $cronCode;
    }

    /**
     * 获取计划任务名称
     */
    public function getCronName(): string
    {
        return $this->cronName;
    }

    /**
     * 设置计划任务名称
     */
    public function setCronName(string $cronName): void
    {
        $this->cronName = $cronName;
    }

    /**
     * 获取计划任务描述
     */
    public function getCronDesc(): string
    {
        return $this->cronDesc;
    }

    /**
     * 设置计划任务描述
     */
    public function setCronDesc(string $cronDesc): void
    {
        $this->cronDesc = $cronDesc;
    }

    /**
     * 获取排序
     */
    public function getCronOrder(): int
    {
        return $this->cronOrder;
    }

    /**
     * 设置排序
     */
    public function setCronOrder(int $cronOrder): void
    {
        $this->cronOrder = $cronOrder;
    }

    /**
     * 获取计划任务配置
     */
    public function getCronConfig(): string
    {
        return $this->cronConfig;
    }

    /**
     * 设置计划任务配置
     */
    public function setCronConfig(string $cronConfig): void
    {
        $this->cronConfig = $cronConfig;
    }

    /**
     * 获取本次执行时间
     */
    public function getThistime(): int
    {
        return $this->thistime;
    }

    /**
     * 设置本次执行时间
     */
    public function setThistime(int $thistime): void
    {
        $this->thistime = $thistime;
    }

    /**
     * 获取下次执行时间
     */
    public function getNextime(): int
    {
        return $this->nextime;
    }

    /**
     * 设置下次执行时间
     */
    public function setNextime(int $nextime): void
    {
        $this->nextime = $nextime;
    }

    /**
     * 获取日
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * 设置日
     */
    public function setDay(int $day): void
    {
        $this->day = $day;
    }

    /**
     * 获取周
     */
    public function getWeek(): string
    {
        return $this->week;
    }

    /**
     * 设置周
     */
    public function setWeek(string $week): void
    {
        $this->week = $week;
    }

    /**
     * 获取时
     */
    public function getHour(): string
    {
        return $this->hour;
    }

    /**
     * 设置时
     */
    public function setHour(string $hour): void
    {
        $this->hour = $hour;
    }

    /**
     * 获取分
     */
    public function getMinute(): string
    {
        return $this->minute;
    }

    /**
     * 设置分
     */
    public function setMinute(string $minute): void
    {
        $this->minute = $minute;
    }

    /**
     * 获取是否启用
     */
    public function getEnable(): int
    {
        return $this->enable;
    }

    /**
     * 设置是否启用
     */
    public function setEnable(int $enable): void
    {
        $this->enable = $enable;
    }

    /**
     * 获取是否只运行一次
     */
    public function getRunOnce(): int
    {
        return $this->runOnce;
    }

    /**
     * 设置是否只运行一次
     */
    public function setRunOnce(int $runOnce): void
    {
        $this->runOnce = $runOnce;
    }

    /**
     * 获取允许的IP
     */
    public function getAllowIp(): string
    {
        return $this->allowIp;
    }

    /**
     * 设置允许的IP
     */
    public function setAllowIp(string $allowIp): void
    {
        $this->allowIp = $allowIp;
    }

    /**
     * 获取允许的文件
     */
    public function getAlowFiles(): string
    {
        return $this->alowFiles;
    }

    /**
     * 设置允许的文件
     */
    public function setAlowFiles(string $alowFiles): void
    {
        $this->alowFiles = $alowFiles;
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
