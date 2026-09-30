<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderShipmentEntity')]
class OrderShipmentEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getMerchantId = 'merchant_id'; // 商家ID

    public const string getLogisticsCompany = 'logistics_company'; // 物流公司

    public const string getTrackingNo = 'tracking_no'; // 物流单号

    public const string getRemark = 'remark'; // 发货备注

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'merchantId', description: '商家ID', type: 'integer')]
    private int $merchantId;

    #[OA\Property(property: 'logisticsCompany', description: '物流公司', type: 'string')]
    private string $logisticsCompany;

    #[OA\Property(property: 'trackingNo', description: '物流单号', type: 'string')]
    private string $trackingNo;

    #[OA\Property(property: 'remark', description: '发货备注', type: 'string')]
    private string $remark;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

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
     * 获取订单ID
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置订单ID
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * 获取商家ID
     */
    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    /**
     * 设置商家ID
     */
    public function setMerchantId(int $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    /**
     * 获取物流公司
     */
    public function getLogisticsCompany(): string
    {
        return $this->logisticsCompany;
    }

    /**
     * 设置物流公司
     */
    public function setLogisticsCompany(string $logisticsCompany): void
    {
        $this->logisticsCompany = $logisticsCompany;
    }

    /**
     * 获取物流单号
     */
    public function getTrackingNo(): string
    {
        return $this->trackingNo;
    }

    /**
     * 设置物流单号
     */
    public function setTrackingNo(string $trackingNo): void
    {
        $this->trackingNo = $trackingNo;
    }

    /**
     * 获取发货备注
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * 设置发货备注
     */
    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
