<?php

declare(strict_types=1);

namespace App\Models\Entity;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CartSchema')]
class Cart
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'seller_id', description: '卖家ID', type: 'integer')]
    protected int $sellerId;

    #[OA\Property(property: 'shop_id', description: '店铺ID', type: 'integer')]
    protected int $shopId;

    #[OA\Property(property: 'user_id', description: '买家ID', type: 'integer')]
    protected int $userId;

    #[OA\Property(property: 'product_id', description: '产品ID', type: 'integer')]
    protected int $productId;

    #[OA\Property(property: 'quantity', description: '商品数量', type: 'integer')]
    protected int $quantity;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取卖家ID
     */
    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    /**
     * 设置卖家ID
     */
    public function setSellerId(int $sellerId): void
    {
        $this->sellerId = $sellerId;
    }

    /**
     * 获取店铺ID
     */
    public function getShopId(): int
    {
        return $this->shopId;
    }

    /**
     * 设置店铺ID
     */
    public function setShopId(int $shopId): void
    {
        $this->shopId = $shopId;
    }

    /**
     * 获取买家ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置买家ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取产品ID
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * 设置产品ID
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * 获取商品数量
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * 设置商品数量
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
