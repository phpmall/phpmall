<?php

declare(strict_types=1);

namespace App\Domains\Product\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductReviewEntity')]
class ProductReviewEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getOrderItemId = 'order_item_id'; // 订单商品项ID

    public const string getProductId = 'product_id'; // 商品ID

    public const string getSkuId = 'sku_id'; // SKU ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getMerchantId = 'merchant_id'; // 商家ID

    public const string getRating = 'rating'; // 评分星级：1-一星，2-二星，3-三星，4-四星，5-五星

    public const string getContent = 'content'; // 评价内容

    public const string getImages = 'images'; // 评价图片

    public const string getIsAnonymous = 'is_anonymous'; // 是否匿名：0-否，1-是

    public const string getIsAppend = 'is_append'; // 是否追评：0-否，1-是

    public const string getParentId = 'parent_id'; // 追评时指向原评价

    public const string getMerchantReply = 'merchant_reply'; // 商家回复

    public const string getMerchantReplyAt = 'merchant_reply_at';

    public const string getStatus = 'status'; // 状态：0-隐藏，1-显示

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    public const string getDeletedAt = 'deleted_at'; // 删除时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'orderItemId', description: '订单商品项ID', type: 'integer')]
    private int $orderItemId;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'skuId', description: 'SKU ID', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'merchantId', description: '商家ID', type: 'integer')]
    private int $merchantId;

    #[OA\Property(property: 'rating', description: '评分星级：1-一星，2-二星，3-三星，4-四星，5-五星', type: 'integer')]
    private int $rating;

    #[OA\Property(property: 'content', description: '评价内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'images', description: '评价图片', type: 'string')]
    private string $images;

    #[OA\Property(property: 'isAnonymous', description: '是否匿名：0-否，1-是', type: 'integer')]
    private int $isAnonymous;

    #[OA\Property(property: 'isAppend', description: '是否追评：0-否，1-是', type: 'integer')]
    private int $isAppend;

    #[OA\Property(property: 'parentId', description: '追评时指向原评价', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'merchantReply', description: '商家回复', type: 'string')]
    private string $merchantReply;

    #[OA\Property(property: 'merchantReplyAt', description: '', type: 'string')]
    private string $merchantReplyAt;

    #[OA\Property(property: 'status', description: '状态：0-隐藏，1-显示', type: 'integer')]
    private int $status;

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
     * 获取订单商品项ID
     */
    public function getOrderItemId(): int
    {
        return $this->orderItemId;
    }

    /**
     * 设置订单商品项ID
     */
    public function setOrderItemId(int $orderItemId): void
    {
        $this->orderItemId = $orderItemId;
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
     * 获取评分星级：1-一星，2-二星，3-三星，4-四星，5-五星
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * 设置评分星级：1-一星，2-二星，3-三星，4-四星，5-五星
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * 获取评价内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置评价内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取评价图片
     */
    public function getImages(): string
    {
        return $this->images;
    }

    /**
     * 设置评价图片
     */
    public function setImages(string $images): void
    {
        $this->images = $images;
    }

    /**
     * 获取是否匿名：0-否，1-是
     */
    public function getIsAnonymous(): int
    {
        return $this->isAnonymous;
    }

    /**
     * 设置是否匿名：0-否，1-是
     */
    public function setIsAnonymous(int $isAnonymous): void
    {
        $this->isAnonymous = $isAnonymous;
    }

    /**
     * 获取是否追评：0-否，1-是
     */
    public function getIsAppend(): int
    {
        return $this->isAppend;
    }

    /**
     * 设置是否追评：0-否，1-是
     */
    public function setIsAppend(int $isAppend): void
    {
        $this->isAppend = $isAppend;
    }

    /**
     * 获取追评时指向原评价
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置追评时指向原评价
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取商家回复
     */
    public function getMerchantReply(): string
    {
        return $this->merchantReply;
    }

    /**
     * 设置商家回复
     */
    public function setMerchantReply(string $merchantReply): void
    {
        $this->merchantReply = $merchantReply;
    }

    /**
     * 获取
     */
    public function getMerchantReplyAt(): string
    {
        return $this->merchantReplyAt;
    }

    /**
     * 设置
     */
    public function setMerchantReplyAt(string $merchantReplyAt): void
    {
        $this->merchantReplyAt = $merchantReplyAt;
    }

    /**
     * 获取状态：0-隐藏，1-显示
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态：0-隐藏，1-显示
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
