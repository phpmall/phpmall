<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopEntity')]
class ShopEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'merchant_id', description: '商户id', type: 'integer')]
    protected int $merchantId;

    #[OA\Property(property: 'shop_name', description: '店铺名称', type: 'string')]
    protected string $shopName;

    #[OA\Property(property: 'owner_name', description: '店主姓名', type: 'string')]
    protected string $ownerName;

    #[OA\Property(property: 'owner_phone', description: '店主电话', type: 'string')]
    protected string $ownerPhone;

    #[OA\Property(property: 'owner_email', description: '店主邮箱', type: 'string')]
    protected string $ownerEmail;

    #[OA\Property(property: 'store_address', description: '店铺地址', type: 'string')]
    protected string $storeAddress;

    #[OA\Property(property: 'store_status', description: '店铺状态：如"正常营业"、"关店维修"', type: 'string')]
    protected string $storeStatus;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deletedAt;

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
     * 获取店铺名称
     */
    public function getShopName(): string
    {
        return $this->shopName;
    }

    /**
     * 设置店铺名称
     */
    public function setShopName(string $shopName): void
    {
        $this->shopName = $shopName;
    }

    /**
     * 获取店主姓名
     */
    public function getOwnerName(): string
    {
        return $this->ownerName;
    }

    /**
     * 设置店主姓名
     */
    public function setOwnerName(string $ownerName): void
    {
        $this->ownerName = $ownerName;
    }

    /**
     * 获取店主电话
     */
    public function getOwnerPhone(): string
    {
        return $this->ownerPhone;
    }

    /**
     * 设置店主电话
     */
    public function setOwnerPhone(string $ownerPhone): void
    {
        $this->ownerPhone = $ownerPhone;
    }

    /**
     * 获取店主邮箱
     */
    public function getOwnerEmail(): string
    {
        return $this->ownerEmail;
    }

    /**
     * 设置店主邮箱
     */
    public function setOwnerEmail(string $ownerEmail): void
    {
        $this->ownerEmail = $ownerEmail;
    }

    /**
     * 获取店铺地址
     */
    public function getStoreAddress(): string
    {
        return $this->storeAddress;
    }

    /**
     * 设置店铺地址
     */
    public function setStoreAddress(string $storeAddress): void
    {
        $this->storeAddress = $storeAddress;
    }

    /**
     * 获取店铺状态：如"正常营业"、"关店维修"
     */
    public function getStoreStatus(): string
    {
        return $this->storeStatus;
    }

    /**
     * 设置店铺状态：如"正常营业"、"关店维修"
     */
    public function setStoreStatus(string $storeStatus): void
    {
        $this->storeStatus = $storeStatus;
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

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
