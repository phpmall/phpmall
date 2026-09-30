<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderEntity')]
class OrderEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOrderNo = 'order_no'; // 订单号

    public const string getUserId = 'user_id'; // 用户ID

    public const string getMerchantId = 'merchant_id'; // 商家ID

    public const string getParentOrderId = 'parent_order_id'; // 父订单ID（拆单）

    public const string getOrderType = 'order_type'; // 订单类型：1-普通，2-秒杀，3-拼团，4-分销

    public const string getStatus = 'status'; // 订单状态：10-待付款，20-已支付，30-待发货，40-已发货，50-待收货，60-已收货，70-已完成，80-已取消，90-退款中，100-已退款

    public const string getPayStatus = 'pay_status'; // 支付状态：0-未支付，20-已支付，30-部分退款，100-全额退款

    public const string getRefundStatus = 'refund_status'; // 退款状态：0-无退款，10-退款申请中，20-退款中，30-已退款，40-拒绝退款

    public const string getProductAmount = 'product_amount'; // 商品总金额（分）

    public const string getDiscountAmount = 'discount_amount'; // 优惠金额（分）

    public const string getFreightAmount = 'freight_amount'; // 运费（分）

    public const string getPayAmount = 'pay_amount'; // 实付金额（分）

    public const string getPayMethod = 'pay_method'; // 支付方式：1-微信，2-支付宝，3-余额，4-银联

    public const string getPayTime = 'pay_time'; // 支付时间

    public const string getPayTransactionId = 'pay_transaction_id'; // 第三方支付流水号

    public const string getShipTime = 'ship_time'; // 发货时间

    public const string getReceiptTime = 'receipt_time'; // 确认收货时间

    public const string getCancelTime = 'cancel_time'; // 取消时间

    public const string getCancelReason = 'cancel_reason'; // 取消原因

    public const string getAutoReceiptTime = 'auto_receipt_time'; // 自动确认收货时间

    public const string getRemark = 'remark'; // 用户备注

    public const string getSource = 'source'; // 订单来源：1-PC，2-H5，3-小程序，4-App

    public const string getSellerRemark = 'seller_remark'; // 商家备注

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    public const string getDeletedAt = 'deleted_at'; // 删除时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderNo', description: '订单号', type: 'string')]
    private string $orderNo;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'merchantId', description: '商家ID', type: 'integer')]
    private int $merchantId;

    #[OA\Property(property: 'parentOrderId', description: '父订单ID（拆单）', type: 'integer')]
    private int $parentOrderId;

    #[OA\Property(property: 'orderType', description: '订单类型：1-普通，2-秒杀，3-拼团，4-分销', type: 'integer')]
    private int $orderType;

    #[OA\Property(property: 'status', description: '订单状态：10-待付款，20-已支付，30-待发货，40-已发货，50-待收货，60-已收货，70-已完成，80-已取消，90-退款中，100-已退款', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'payStatus', description: '支付状态：0-未支付，20-已支付，30-部分退款，100-全额退款', type: 'integer')]
    private int $payStatus;

    #[OA\Property(property: 'refundStatus', description: '退款状态：0-无退款，10-退款申请中，20-退款中，30-已退款，40-拒绝退款', type: 'integer')]
    private int $refundStatus;

    #[OA\Property(property: 'productAmount', description: '商品总金额（分）', type: 'integer')]
    private int $productAmount;

    #[OA\Property(property: 'discountAmount', description: '优惠金额（分）', type: 'integer')]
    private int $discountAmount;

    #[OA\Property(property: 'freightAmount', description: '运费（分）', type: 'integer')]
    private int $freightAmount;

    #[OA\Property(property: 'payAmount', description: '实付金额（分）', type: 'integer')]
    private int $payAmount;

    #[OA\Property(property: 'payMethod', description: '支付方式：1-微信，2-支付宝，3-余额，4-银联', type: 'integer')]
    private int $payMethod;

    #[OA\Property(property: 'payTime', description: '支付时间', type: 'string')]
    private string $payTime;

    #[OA\Property(property: 'payTransactionId', description: '第三方支付流水号', type: 'string')]
    private string $payTransactionId;

    #[OA\Property(property: 'shipTime', description: '发货时间', type: 'string')]
    private string $shipTime;

    #[OA\Property(property: 'receiptTime', description: '确认收货时间', type: 'string')]
    private string $receiptTime;

    #[OA\Property(property: 'cancelTime', description: '取消时间', type: 'string')]
    private string $cancelTime;

    #[OA\Property(property: 'cancelReason', description: '取消原因', type: 'string')]
    private string $cancelReason;

    #[OA\Property(property: 'autoReceiptTime', description: '自动确认收货时间', type: 'string')]
    private string $autoReceiptTime;

    #[OA\Property(property: 'remark', description: '用户备注', type: 'string')]
    private string $remark;

    #[OA\Property(property: 'source', description: '订单来源：1-PC，2-H5，3-小程序，4-App', type: 'integer')]
    private int $source;

    #[OA\Property(property: 'sellerRemark', description: '商家备注', type: 'string')]
    private string $sellerRemark;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '删除时间', type: 'string')]
    private string $deletedAt;

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
     * 获取订单号
     */
    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    /**
     * 设置订单号
     */
    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    /**
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
     * 获取父订单ID（拆单）
     */
    public function getParentOrderId(): int
    {
        return $this->parentOrderId;
    }

    /**
     * 设置父订单ID（拆单）
     */
    public function setParentOrderId(int $parentOrderId): void
    {
        $this->parentOrderId = $parentOrderId;
    }

    /**
     * 获取订单类型：1-普通，2-秒杀，3-拼团，4-分销
     */
    public function getOrderType(): int
    {
        return $this->orderType;
    }

    /**
     * 设置订单类型：1-普通，2-秒杀，3-拼团，4-分销
     */
    public function setOrderType(int $orderType): void
    {
        $this->orderType = $orderType;
    }

    /**
     * 获取订单状态：10-待付款，20-已支付，30-待发货，40-已发货，50-待收货，60-已收货，70-已完成，80-已取消，90-退款中，100-已退款
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置订单状态：10-待付款，20-已支付，30-待发货，40-已发货，50-待收货，60-已收货，70-已完成，80-已取消，90-退款中，100-已退款
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取支付状态：0-未支付，20-已支付，30-部分退款，100-全额退款
     */
    public function getPayStatus(): int
    {
        return $this->payStatus;
    }

    /**
     * 设置支付状态：0-未支付，20-已支付，30-部分退款，100-全额退款
     */
    public function setPayStatus(int $payStatus): void
    {
        $this->payStatus = $payStatus;
    }

    /**
     * 获取退款状态：0-无退款，10-退款申请中，20-退款中，30-已退款，40-拒绝退款
     */
    public function getRefundStatus(): int
    {
        return $this->refundStatus;
    }

    /**
     * 设置退款状态：0-无退款，10-退款申请中，20-退款中，30-已退款，40-拒绝退款
     */
    public function setRefundStatus(int $refundStatus): void
    {
        $this->refundStatus = $refundStatus;
    }

    /**
     * 获取商品总金额（分）
     */
    public function getProductAmount(): int
    {
        return $this->productAmount;
    }

    /**
     * 设置商品总金额（分）
     */
    public function setProductAmount(int $productAmount): void
    {
        $this->productAmount = $productAmount;
    }

    /**
     * 获取优惠金额（分）
     */
    public function getDiscountAmount(): int
    {
        return $this->discountAmount;
    }

    /**
     * 设置优惠金额（分）
     */
    public function setDiscountAmount(int $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * 获取运费（分）
     */
    public function getFreightAmount(): int
    {
        return $this->freightAmount;
    }

    /**
     * 设置运费（分）
     */
    public function setFreightAmount(int $freightAmount): void
    {
        $this->freightAmount = $freightAmount;
    }

    /**
     * 获取实付金额（分）
     */
    public function getPayAmount(): int
    {
        return $this->payAmount;
    }

    /**
     * 设置实付金额（分）
     */
    public function setPayAmount(int $payAmount): void
    {
        $this->payAmount = $payAmount;
    }

    /**
     * 获取支付方式：1-微信，2-支付宝，3-余额，4-银联
     */
    public function getPayMethod(): int
    {
        return $this->payMethod;
    }

    /**
     * 设置支付方式：1-微信，2-支付宝，3-余额，4-银联
     */
    public function setPayMethod(int $payMethod): void
    {
        $this->payMethod = $payMethod;
    }

    /**
     * 获取支付时间
     */
    public function getPayTime(): string
    {
        return $this->payTime;
    }

    /**
     * 设置支付时间
     */
    public function setPayTime(string $payTime): void
    {
        $this->payTime = $payTime;
    }

    /**
     * 获取第三方支付流水号
     */
    public function getPayTransactionId(): string
    {
        return $this->payTransactionId;
    }

    /**
     * 设置第三方支付流水号
     */
    public function setPayTransactionId(string $payTransactionId): void
    {
        $this->payTransactionId = $payTransactionId;
    }

    /**
     * 获取发货时间
     */
    public function getShipTime(): string
    {
        return $this->shipTime;
    }

    /**
     * 设置发货时间
     */
    public function setShipTime(string $shipTime): void
    {
        $this->shipTime = $shipTime;
    }

    /**
     * 获取确认收货时间
     */
    public function getReceiptTime(): string
    {
        return $this->receiptTime;
    }

    /**
     * 设置确认收货时间
     */
    public function setReceiptTime(string $receiptTime): void
    {
        $this->receiptTime = $receiptTime;
    }

    /**
     * 获取取消时间
     */
    public function getCancelTime(): string
    {
        return $this->cancelTime;
    }

    /**
     * 设置取消时间
     */
    public function setCancelTime(string $cancelTime): void
    {
        $this->cancelTime = $cancelTime;
    }

    /**
     * 获取取消原因
     */
    public function getCancelReason(): string
    {
        return $this->cancelReason;
    }

    /**
     * 设置取消原因
     */
    public function setCancelReason(string $cancelReason): void
    {
        $this->cancelReason = $cancelReason;
    }

    /**
     * 获取自动确认收货时间
     */
    public function getAutoReceiptTime(): string
    {
        return $this->autoReceiptTime;
    }

    /**
     * 设置自动确认收货时间
     */
    public function setAutoReceiptTime(string $autoReceiptTime): void
    {
        $this->autoReceiptTime = $autoReceiptTime;
    }

    /**
     * 获取用户备注
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * 设置用户备注
     */
    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * 获取订单来源：1-PC，2-H5，3-小程序，4-App
     */
    public function getSource(): int
    {
        return $this->source;
    }

    /**
     * 设置订单来源：1-PC，2-H5，3-小程序，4-App
     */
    public function setSource(int $source): void
    {
        $this->source = $source;
    }

    /**
     * 获取商家备注
     */
    public function getSellerRemark(): string
    {
        return $this->sellerRemark;
    }

    /**
     * 设置商家备注
     */
    public function setSellerRemark(string $sellerRemark): void
    {
        $this->sellerRemark = $sellerRemark;
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

    /**
     * 获取删除时间
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置删除时间
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
