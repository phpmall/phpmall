<?php

declare(strict_types=1);

namespace App\Domains\Order\Responses\OrderItem;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderItemResponse')]
class OrderItemResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'skuId', description: 'SKU ID', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'merchantId', description: '商家ID', type: 'integer')]
    private int $merchantId;

    #[OA\Property(property: 'productTitle', description: '商品标题（快照）', type: 'string')]
    private string $productTitle;

    #[OA\Property(property: 'productImage', description: '商品主图（快照）', type: 'string')]
    private string $productImage;

    #[OA\Property(property: 'skuSpecs', description: '规格快照', type: 'string')]
    private string $skuSpecs;

    #[OA\Property(property: 'price', description: '下单时单价（分）', type: 'integer')]
    private int $price;

    #[OA\Property(property: 'quantity', description: '数量', type: 'integer')]
    private int $quantity;

    #[OA\Property(property: 'totalAmount', description: '小计（分）', type: 'integer')]
    private int $totalAmount;

    #[OA\Property(property: 'discountAmount', description: '优惠分摊（分）', type: 'integer')]
    private int $discountAmount;

    #[OA\Property(property: 'refundAmount', description: '已退款金额（分）', type: 'integer')]
    private int $refundAmount;

    #[OA\Property(property: 'refundStatus', description: '售后状态：0-无售后，1-申请中，2-已退款，3-已拒绝', type: 'integer')]
    private int $refundStatus;

    #[OA\Property(property: 'isCommented', description: '是否评价：0-未评价，1-已评价', type: 'integer')]
    private int $isCommented;

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
     * 获取商品ID
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * 设置商品ID
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * 获取SKU ID
     */
    public function getSkuId(): int
    {
        return $this->skuId;
    }

    /**
     * 设置SKU ID
     */
    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
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
     * 获取商品标题（快照）
     */
    public function getProductTitle(): string
    {
        return $this->productTitle;
    }

    /**
     * 设置商品标题（快照）
     */
    public function setProductTitle(string $productTitle): void
    {
        $this->productTitle = $productTitle;
    }

    /**
     * 获取商品主图（快照）
     */
    public function getProductImage(): string
    {
        return $this->productImage;
    }

    /**
     * 设置商品主图（快照）
     */
    public function setProductImage(string $productImage): void
    {
        $this->productImage = $productImage;
    }

    /**
     * 获取规格快照
     */
    public function getSkuSpecs(): string
    {
        return $this->skuSpecs;
    }

    /**
     * 设置规格快照
     */
    public function setSkuSpecs(string $skuSpecs): void
    {
        $this->skuSpecs = $skuSpecs;
    }

    /**
     * 获取下单时单价（分）
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * 设置下单时单价（分）
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * 获取数量
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * 设置数量
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * 获取小计（分）
     */
    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    /**
     * 设置小计（分）
     */
    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * 获取优惠分摊（分）
     */
    public function getDiscountAmount(): int
    {
        return $this->discountAmount;
    }

    /**
     * 设置优惠分摊（分）
     */
    public function setDiscountAmount(int $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * 获取已退款金额（分）
     */
    public function getRefundAmount(): int
    {
        return $this->refundAmount;
    }

    /**
     * 设置已退款金额（分）
     */
    public function setRefundAmount(int $refundAmount): void
    {
        $this->refundAmount = $refundAmount;
    }

    /**
     * 获取售后状态：0-无售后，1-申请中，2-已退款，3-已拒绝
     */
    public function getRefundStatus(): int
    {
        return $this->refundStatus;
    }

    /**
     * 设置售后状态：0-无售后，1-申请中，2-已退款，3-已拒绝
     */
    public function setRefundStatus(int $refundStatus): void
    {
        $this->refundStatus = $refundStatus;
    }

    /**
     * 获取是否评价：0-未评价，1-已评价
     */
    public function getIsCommented(): int
    {
        return $this->isCommented;
    }

    /**
     * 设置是否评价：0-未评价，1-已评价
     */
    public function setIsCommented(int $isCommented): void
    {
        $this->isCommented = $isCommented;
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
