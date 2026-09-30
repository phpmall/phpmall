<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderRefundEntity')]
class OrderRefundEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getRefundNo = 'refund_no'; // 退款单号

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getOrderItemId = 'order_item_id'; // 订单商品项ID，可为空（整单退款）

    public const string getUserId = 'user_id'; // 用户ID

    public const string getMerchantId = 'merchant_id'; // 商家ID

    public const string getType = 'type'; // 1=仅退款 2=退货退款 3=换货

    public const string getReason = 'reason'; // 退款原因

    public const string getReasonType = 'reason_type'; // 原因分类

    public const string getDescription = 'description'; // 补充说明

    public const string getImages = 'images'; // 凭证图片

    public const string getApplyAmount = 'apply_amount'; // 申请退款金额（分）

    public const string getRefundAmount = 'refund_amount'; // 实际退款金额（分）

    public const string getStatus = 'status'; // 0=待商家处理 1=商家同意 2=商家拒绝 3=退货中 4=平台介入 5=已退款 6=已拒绝 7=用户撤销

    public const string getMerchantRemark = 'merchant_remark'; // 商家处理备注

    public const string getPlatformRemark = 'platform_remark'; // 平台仲裁备注

    public const string getReturnExpressCompany = 'return_express_company'; // 退货快递公司

    public const string getReturnExpressNo = 'return_express_no'; // 退货快递单号

    public const string getReturnShipTime = 'return_ship_time'; // 用户退货发货时间

    public const string getMerchantReceiptTime = 'merchant_receipt_time'; // 商家收到退货时间

    public const string getRefundTime = 'refund_time'; // 实际退款时间

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'refundNo', description: '退款单号', type: 'string')]
    private string $refundNo;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'orderItemId', description: '订单商品项ID，可为空（整单退款）', type: 'integer')]
    private int $orderItemId;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'merchantId', description: '商家ID', type: 'integer')]
    private int $merchantId;

    #[OA\Property(property: 'type', description: '1=仅退款 2=退货退款 3=换货', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'reason', description: '退款原因', type: 'string')]
    private string $reason;

    #[OA\Property(property: 'reasonType', description: '原因分类', type: 'integer')]
    private int $reasonType;

    #[OA\Property(property: 'description', description: '补充说明', type: 'string')]
    private string $description;

    #[OA\Property(property: 'images', description: '凭证图片', type: 'string')]
    private string $images;

    #[OA\Property(property: 'applyAmount', description: '申请退款金额（分）', type: 'integer')]
    private int $applyAmount;

    #[OA\Property(property: 'refundAmount', description: '实际退款金额（分）', type: 'integer')]
    private int $refundAmount;

    #[OA\Property(property: 'status', description: '0=待商家处理 1=商家同意 2=商家拒绝 3=退货中 4=平台介入 5=已退款 6=已拒绝 7=用户撤销', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'merchantRemark', description: '商家处理备注', type: 'string')]
    private string $merchantRemark;

    #[OA\Property(property: 'platformRemark', description: '平台仲裁备注', type: 'string')]
    private string $platformRemark;

    #[OA\Property(property: 'returnExpressCompany', description: '退货快递公司', type: 'string')]
    private string $returnExpressCompany;

    #[OA\Property(property: 'returnExpressNo', description: '退货快递单号', type: 'string')]
    private string $returnExpressNo;

    #[OA\Property(property: 'returnShipTime', description: '用户退货发货时间', type: 'string')]
    private string $returnShipTime;

    #[OA\Property(property: 'merchantReceiptTime', description: '商家收到退货时间', type: 'string')]
    private string $merchantReceiptTime;

    #[OA\Property(property: 'refundTime', description: '实际退款时间', type: 'string')]
    private string $refundTime;

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
     * 获取退款单号
     */
    public function getRefundNo(): string
    {
        return $this->refundNo;
    }

    /**
     * 设置退款单号
     */
    public function setRefundNo(string $refundNo): void
    {
        $this->refundNo = $refundNo;
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
     * 获取订单商品项ID，可为空（整单退款）
     */
    public function getOrderItemId(): int
    {
        return $this->orderItemId;
    }

    /**
     * 设置订单商品项ID，可为空（整单退款）
     */
    public function setOrderItemId(int $orderItemId): void
    {
        $this->orderItemId = $orderItemId;
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
     * 获取1=仅退款 2=退货退款 3=换货
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置1=仅退款 2=退货退款 3=换货
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取退款原因
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * 设置退款原因
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * 获取原因分类
     */
    public function getReasonType(): int
    {
        return $this->reasonType;
    }

    /**
     * 设置原因分类
     */
    public function setReasonType(int $reasonType): void
    {
        $this->reasonType = $reasonType;
    }

    /**
     * 获取补充说明
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置补充说明
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取凭证图片
     */
    public function getImages(): string
    {
        return $this->images;
    }

    /**
     * 设置凭证图片
     */
    public function setImages(string $images): void
    {
        $this->images = $images;
    }

    /**
     * 获取申请退款金额（分）
     */
    public function getApplyAmount(): int
    {
        return $this->applyAmount;
    }

    /**
     * 设置申请退款金额（分）
     */
    public function setApplyAmount(int $applyAmount): void
    {
        $this->applyAmount = $applyAmount;
    }

    /**
     * 获取实际退款金额（分）
     */
    public function getRefundAmount(): int
    {
        return $this->refundAmount;
    }

    /**
     * 设置实际退款金额（分）
     */
    public function setRefundAmount(int $refundAmount): void
    {
        $this->refundAmount = $refundAmount;
    }

    /**
     * 获取0=待商家处理 1=商家同意 2=商家拒绝 3=退货中 4=平台介入 5=已退款 6=已拒绝 7=用户撤销
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置0=待商家处理 1=商家同意 2=商家拒绝 3=退货中 4=平台介入 5=已退款 6=已拒绝 7=用户撤销
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取商家处理备注
     */
    public function getMerchantRemark(): string
    {
        return $this->merchantRemark;
    }

    /**
     * 设置商家处理备注
     */
    public function setMerchantRemark(string $merchantRemark): void
    {
        $this->merchantRemark = $merchantRemark;
    }

    /**
     * 获取平台仲裁备注
     */
    public function getPlatformRemark(): string
    {
        return $this->platformRemark;
    }

    /**
     * 设置平台仲裁备注
     */
    public function setPlatformRemark(string $platformRemark): void
    {
        $this->platformRemark = $platformRemark;
    }

    /**
     * 获取退货快递公司
     */
    public function getReturnExpressCompany(): string
    {
        return $this->returnExpressCompany;
    }

    /**
     * 设置退货快递公司
     */
    public function setReturnExpressCompany(string $returnExpressCompany): void
    {
        $this->returnExpressCompany = $returnExpressCompany;
    }

    /**
     * 获取退货快递单号
     */
    public function getReturnExpressNo(): string
    {
        return $this->returnExpressNo;
    }

    /**
     * 设置退货快递单号
     */
    public function setReturnExpressNo(string $returnExpressNo): void
    {
        $this->returnExpressNo = $returnExpressNo;
    }

    /**
     * 获取用户退货发货时间
     */
    public function getReturnShipTime(): string
    {
        return $this->returnShipTime;
    }

    /**
     * 设置用户退货发货时间
     */
    public function setReturnShipTime(string $returnShipTime): void
    {
        $this->returnShipTime = $returnShipTime;
    }

    /**
     * 获取商家收到退货时间
     */
    public function getMerchantReceiptTime(): string
    {
        return $this->merchantReceiptTime;
    }

    /**
     * 设置商家收到退货时间
     */
    public function setMerchantReceiptTime(string $merchantReceiptTime): void
    {
        $this->merchantReceiptTime = $merchantReceiptTime;
    }

    /**
     * 获取实际退款时间
     */
    public function getRefundTime(): string
    {
        return $this->refundTime;
    }

    /**
     * 设置实际退款时间
     */
    public function setRefundTime(string $refundTime): void
    {
        $this->refundTime = $refundTime;
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
