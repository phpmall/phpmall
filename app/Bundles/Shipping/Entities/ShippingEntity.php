<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShippingEntity')]
class ShippingEntity
{
    use DTOHelper;

    const string getShippingId = 'shipping_id';

    const string getShippingCode = 'shipping_code'; // 配送代码

    const string getShippingName = 'shipping_name'; // 配送名称

    const string getShippingDesc = 'shipping_desc'; // 配送描述

    const string getInsure = 'insure'; // 保价

    const string getSupportCod = 'support_cod'; // 是否支持货到付款

    const string getEnabled = 'enabled'; // 是否启用

    const string getShippingPrint = 'shipping_print'; // 打印模板

    const string getPrintBg = 'print_bg'; // 打印背景

    const string getConfigLabel = 'config_label'; // 配置标签

    const string getPrintModel = 'print_model'; // 打印模式

    const string getShippingOrder = 'shipping_order'; // 排序

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'shippingId', description: '', type: 'integer')]
    private int $shippingId;

    #[OA\Property(property: 'shippingCode', description: '配送代码', type: 'string')]
    private string $shippingCode;

    #[OA\Property(property: 'shippingName', description: '配送名称', type: 'string')]
    private string $shippingName;

    #[OA\Property(property: 'shippingDesc', description: '配送描述', type: 'string')]
    private string $shippingDesc;

    #[OA\Property(property: 'insure', description: '保价', type: 'string')]
    private string $insure;

    #[OA\Property(property: 'supportCod', description: '是否支持货到付款', type: 'integer')]
    private int $supportCod;

    #[OA\Property(property: 'enabled', description: '是否启用', type: 'integer')]
    private int $enabled;

    #[OA\Property(property: 'shippingPrint', description: '打印模板', type: 'string')]
    private string $shippingPrint;

    #[OA\Property(property: 'printBg', description: '打印背景', type: 'string')]
    private string $printBg;

    #[OA\Property(property: 'configLabel', description: '配置标签', type: 'string')]
    private string $configLabel;

    #[OA\Property(property: 'printModel', description: '打印模式', type: 'integer')]
    private int $printModel;

    #[OA\Property(property: 'shippingOrder', description: '排序', type: 'integer')]
    private int $shippingOrder;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getShippingId(): int
    {
        return $this->shippingId;
    }

    /**
     * 设置
     */
    public function setShippingId(int $shippingId): void
    {
        $this->shippingId = $shippingId;
    }

    /**
     * 获取配送代码
     */
    public function getShippingCode(): string
    {
        return $this->shippingCode;
    }

    /**
     * 设置配送代码
     */
    public function setShippingCode(string $shippingCode): void
    {
        $this->shippingCode = $shippingCode;
    }

    /**
     * 获取配送名称
     */
    public function getShippingName(): string
    {
        return $this->shippingName;
    }

    /**
     * 设置配送名称
     */
    public function setShippingName(string $shippingName): void
    {
        $this->shippingName = $shippingName;
    }

    /**
     * 获取配送描述
     */
    public function getShippingDesc(): string
    {
        return $this->shippingDesc;
    }

    /**
     * 设置配送描述
     */
    public function setShippingDesc(string $shippingDesc): void
    {
        $this->shippingDesc = $shippingDesc;
    }

    /**
     * 获取保价
     */
    public function getInsure(): string
    {
        return $this->insure;
    }

    /**
     * 设置保价
     */
    public function setInsure(string $insure): void
    {
        $this->insure = $insure;
    }

    /**
     * 获取是否支持货到付款
     */
    public function getSupportCod(): int
    {
        return $this->supportCod;
    }

    /**
     * 设置是否支持货到付款
     */
    public function setSupportCod(int $supportCod): void
    {
        $this->supportCod = $supportCod;
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
     * 获取打印模板
     */
    public function getShippingPrint(): string
    {
        return $this->shippingPrint;
    }

    /**
     * 设置打印模板
     */
    public function setShippingPrint(string $shippingPrint): void
    {
        $this->shippingPrint = $shippingPrint;
    }

    /**
     * 获取打印背景
     */
    public function getPrintBg(): string
    {
        return $this->printBg;
    }

    /**
     * 设置打印背景
     */
    public function setPrintBg(string $printBg): void
    {
        $this->printBg = $printBg;
    }

    /**
     * 获取配置标签
     */
    public function getConfigLabel(): string
    {
        return $this->configLabel;
    }

    /**
     * 设置配置标签
     */
    public function setConfigLabel(string $configLabel): void
    {
        $this->configLabel = $configLabel;
    }

    /**
     * 获取打印模式
     */
    public function getPrintModel(): int
    {
        return $this->printModel;
    }

    /**
     * 设置打印模式
     */
    public function setPrintModel(int $printModel): void
    {
        $this->printModel = $printModel;
    }

    /**
     * 获取排序
     */
    public function getShippingOrder(): int
    {
        return $this->shippingOrder;
    }

    /**
     * 设置排序
     */
    public function setShippingOrder(int $shippingOrder): void
    {
        $this->shippingOrder = $shippingOrder;
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
