<?php

declare(strict_types=1);

namespace App\Bundles\Order\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderPayEntity')]
class OrderPayEntity
{
    use DTOHelper;

    const string getLogId = 'log_id';

    const string getOrderId = 'order_id'; // 订单ID

    const string getOrderAmount = 'order_amount'; // 订单金额

    const string getOrderType = 'order_type'; // 订单类型

    const string getIsPaid = 'is_paid'; // 是否已支付

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'logId', description: '', type: 'integer')]
    private int $logId;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'orderAmount', description: '订单金额', type: 'string')]
    private string $orderAmount;

    #[OA\Property(property: 'orderType', description: '订单类型', type: 'integer')]
    private int $orderType;

    #[OA\Property(property: 'isPaid', description: '是否已支付', type: 'integer')]
    private int $isPaid;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getLogId(): int
    {
        return $this->logId;
    }

    /**
     * 设置
     */
    public function setLogId(int $logId): void
    {
        $this->logId = $logId;
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
     * 获取订单金额
     */
    public function getOrderAmount(): string
    {
        return $this->orderAmount;
    }

    /**
     * 设置订单金额
     */
    public function setOrderAmount(string $orderAmount): void
    {
        $this->orderAmount = $orderAmount;
    }

    /**
     * 获取订单类型
     */
    public function getOrderType(): int
    {
        return $this->orderType;
    }

    /**
     * 设置订单类型
     */
    public function setOrderType(int $orderType): void
    {
        $this->orderType = $orderType;
    }

    /**
     * 获取是否已支付
     */
    public function getIsPaid(): int
    {
        return $this->isPaid;
    }

    /**
     * 设置是否已支付
     */
    public function setIsPaid(int $isPaid): void
    {
        $this->isPaid = $isPaid;
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
