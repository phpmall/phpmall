<?php

declare(strict_types=1);

namespace App\Bundles\Order\Responses\OrderAction;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderActionResponse')]
class OrderActionResponse
{
    use DTOHelper;

    #[OA\Property(property: 'actionId', description: '', type: 'integer')]
    private int $actionId;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'actionUser', description: '操作用户', type: 'string')]
    private string $actionUser;

    #[OA\Property(property: 'orderStatus', description: '订单状态', type: 'integer')]
    private int $orderStatus;

    #[OA\Property(property: 'shippingStatus', description: '配送状态', type: 'integer')]
    private int $shippingStatus;

    #[OA\Property(property: 'payStatus', description: '支付状态', type: 'integer')]
    private int $payStatus;

    #[OA\Property(property: 'actionPlace', description: '操作位置', type: 'integer')]
    private int $actionPlace;

    #[OA\Property(property: 'actionNote', description: '操作备注', type: 'string')]
    private string $actionNote;

    #[OA\Property(property: 'logTime', description: '日志时间', type: 'integer')]
    private int $logTime;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getActionId(): int
    {
        return $this->actionId;
    }

    /**
     * 设置
     */
    public function setActionId(int $actionId): void
    {
        $this->actionId = $actionId;
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
     * 获取操作用户
     */
    public function getActionUser(): string
    {
        return $this->actionUser;
    }

    /**
     * 设置操作用户
     */
    public function setActionUser(string $actionUser): void
    {
        $this->actionUser = $actionUser;
    }

    /**
     * 获取订单状态
     */
    public function getOrderStatus(): int
    {
        return $this->orderStatus;
    }

    /**
     * 设置订单状态
     */
    public function setOrderStatus(int $orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }

    /**
     * 获取配送状态
     */
    public function getShippingStatus(): int
    {
        return $this->shippingStatus;
    }

    /**
     * 设置配送状态
     */
    public function setShippingStatus(int $shippingStatus): void
    {
        $this->shippingStatus = $shippingStatus;
    }

    /**
     * 获取支付状态
     */
    public function getPayStatus(): int
    {
        return $this->payStatus;
    }

    /**
     * 设置支付状态
     */
    public function setPayStatus(int $payStatus): void
    {
        $this->payStatus = $payStatus;
    }

    /**
     * 获取操作位置
     */
    public function getActionPlace(): int
    {
        return $this->actionPlace;
    }

    /**
     * 设置操作位置
     */
    public function setActionPlace(int $actionPlace): void
    {
        $this->actionPlace = $actionPlace;
    }

    /**
     * 获取操作备注
     */
    public function getActionNote(): string
    {
        return $this->actionNote;
    }

    /**
     * 设置操作备注
     */
    public function setActionNote(string $actionNote): void
    {
        $this->actionNote = $actionNote;
    }

    /**
     * 获取日志时间
     */
    public function getLogTime(): int
    {
        return $this->logTime;
    }

    /**
     * 设置日志时间
     */
    public function setLogTime(int $logTime): void
    {
        $this->logTime = $logTime;
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
