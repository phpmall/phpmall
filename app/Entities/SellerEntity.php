<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Generator\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerEntity')]
class SellerEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'merchant_id', description: '商户id', type: 'integer')]
    protected int $merchantId;

    #[OA\Property(property: 'shop_id', description: '店铺id', type: 'integer')]
    protected int $shopId;

    #[OA\Property(property: 'store_id', description: '门店ID', type: 'integer')]
    protected int $storeId;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $userId;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'string')]
    protected string $status;

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
     * 获取商户id
     */
    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    /**
     * 设置商户id
     */
    public function setMerchantId(int $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    /**
     * 获取店铺id
     */
    public function getShopId(): int
    {
        return $this->shopId;
    }

    /**
     * 设置店铺id
     */
    public function setShopId(int $shopId): void
    {
        $this->shopId = $shopId;
    }

    /**
     * 获取门店ID
     */
    public function getStoreId(): int
    {
        return $this->storeId;
    }

    /**
     * 设置门店ID
     */
    public function setStoreId(int $storeId): void
    {
        $this->storeId = $storeId;
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
     * 获取状态:1正常,2禁用
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * 设置状态:1正常,2禁用
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
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
