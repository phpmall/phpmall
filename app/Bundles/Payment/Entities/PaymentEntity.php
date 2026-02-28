<?php

declare(strict_types=1);

namespace App\Bundles\Payment\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PaymentEntity')]
class PaymentEntity
{
    use DTOHelper;

    const string getPayId = 'pay_id';

    const string getPayCode = 'pay_code'; // 支付方式编码

    const string getPayName = 'pay_name'; // 支付名称

    const string getPayFee = 'pay_fee'; // 支付手续费

    const string getPayDesc = 'pay_desc'; // 支付描述

    const string getPayOrder = 'pay_order'; // 排序

    const string getPayConfig = 'pay_config'; // 支付配置

    const string getEnabled = 'enabled'; // 是否启用

    const string getIsCod = 'is_cod'; // 是否货到付款

    const string getIsOnline = 'is_online'; // 是否在线支付

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'payId', description: '', type: 'integer')]
    private int $payId;

    #[OA\Property(property: 'payCode', description: '支付方式编码', type: 'string')]
    private string $payCode;

    #[OA\Property(property: 'payName', description: '支付名称', type: 'string')]
    private string $payName;

    #[OA\Property(property: 'payFee', description: '支付手续费', type: 'string')]
    private string $payFee;

    #[OA\Property(property: 'payDesc', description: '支付描述', type: 'string')]
    private string $payDesc;

    #[OA\Property(property: 'payOrder', description: '排序', type: 'integer')]
    private int $payOrder;

    #[OA\Property(property: 'payConfig', description: '支付配置', type: 'string')]
    private string $payConfig;

    #[OA\Property(property: 'enabled', description: '是否启用', type: 'integer')]
    private int $enabled;

    #[OA\Property(property: 'isCod', description: '是否货到付款', type: 'integer')]
    private int $isCod;

    #[OA\Property(property: 'isOnline', description: '是否在线支付', type: 'integer')]
    private int $isOnline;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getPayId(): int
    {
        return $this->payId;
    }

    /**
     * 设置
     */
    public function setPayId(int $payId): void
    {
        $this->payId = $payId;
    }

    /**
     * 获取支付方式编码
     */
    public function getPayCode(): string
    {
        return $this->payCode;
    }

    /**
     * 设置支付方式编码
     */
    public function setPayCode(string $payCode): void
    {
        $this->payCode = $payCode;
    }

    /**
     * 获取支付名称
     */
    public function getPayName(): string
    {
        return $this->payName;
    }

    /**
     * 设置支付名称
     */
    public function setPayName(string $payName): void
    {
        $this->payName = $payName;
    }

    /**
     * 获取支付手续费
     */
    public function getPayFee(): string
    {
        return $this->payFee;
    }

    /**
     * 设置支付手续费
     */
    public function setPayFee(string $payFee): void
    {
        $this->payFee = $payFee;
    }

    /**
     * 获取支付描述
     */
    public function getPayDesc(): string
    {
        return $this->payDesc;
    }

    /**
     * 设置支付描述
     */
    public function setPayDesc(string $payDesc): void
    {
        $this->payDesc = $payDesc;
    }

    /**
     * 获取排序
     */
    public function getPayOrder(): int
    {
        return $this->payOrder;
    }

    /**
     * 设置排序
     */
    public function setPayOrder(int $payOrder): void
    {
        $this->payOrder = $payOrder;
    }

    /**
     * 获取支付配置
     */
    public function getPayConfig(): string
    {
        return $this->payConfig;
    }

    /**
     * 设置支付配置
     */
    public function setPayConfig(string $payConfig): void
    {
        $this->payConfig = $payConfig;
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
     * 获取是否货到付款
     */
    public function getIsCod(): int
    {
        return $this->isCod;
    }

    /**
     * 设置是否货到付款
     */
    public function setIsCod(int $isCod): void
    {
        $this->isCod = $isCod;
    }

    /**
     * 获取是否在线支付
     */
    public function getIsOnline(): int
    {
        return $this->isOnline;
    }

    /**
     * 设置是否在线支付
     */
    public function setIsOnline(int $isOnline): void
    {
        $this->isOnline = $isOnline;
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
