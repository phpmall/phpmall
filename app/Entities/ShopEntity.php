<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopEntity')]
class ShopEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'merchant_id', description: '商户id', type: 'integer')]
    protected int $merchant_id;

    #[OA\Property(property: 'shop_name', description: '店铺名称', type: 'string')]
    protected string $shop_name;

    #[OA\Property(property: 'owner_name', description: '店主姓名', type: 'string')]
    protected string $owner_name;

    #[OA\Property(property: 'owner_phone', description: '店主电话', type: 'string')]
    protected string $owner_phone;

    #[OA\Property(property: 'owner_email', description: '店主邮箱', type: 'string')]
    protected string $owner_email;

    #[OA\Property(property: 'store_address', description: '店铺地址', type: 'string')]
    protected string $store_address;

    #[OA\Property(property: 'store_status', description: '店铺状态：如"正常营业"、"关店维修"', type: 'string')]
    protected string $store_status;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deleted_at;

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
        return $this->merchant_id;
    }

    /**
     * 设置商户id
     */
    public function setMerchantId(int $merchant_id): void
    {
        $this->merchant_id = $merchant_id;
    }

    /**
     * 获取店铺名称
     */
    public function getShopName(): string
    {
        return $this->shop_name;
    }

    /**
     * 设置店铺名称
     */
    public function setShopName(string $shop_name): void
    {
        $this->shop_name = $shop_name;
    }

    /**
     * 获取店主姓名
     */
    public function getOwnerName(): string
    {
        return $this->owner_name;
    }

    /**
     * 设置店主姓名
     */
    public function setOwnerName(string $owner_name): void
    {
        $this->owner_name = $owner_name;
    }

    /**
     * 获取店主电话
     */
    public function getOwnerPhone(): string
    {
        return $this->owner_phone;
    }

    /**
     * 设置店主电话
     */
    public function setOwnerPhone(string $owner_phone): void
    {
        $this->owner_phone = $owner_phone;
    }

    /**
     * 获取店主邮箱
     */
    public function getOwnerEmail(): string
    {
        return $this->owner_email;
    }

    /**
     * 设置店主邮箱
     */
    public function setOwnerEmail(string $owner_email): void
    {
        $this->owner_email = $owner_email;
    }

    /**
     * 获取店铺地址
     */
    public function getStoreAddress(): string
    {
        return $this->store_address;
    }

    /**
     * 设置店铺地址
     */
    public function setStoreAddress(string $store_address): void
    {
        $this->store_address = $store_address;
    }

    /**
     * 获取店铺状态：如"正常营业"、"关店维修"
     */
    public function getStoreStatus(): string
    {
        return $this->store_status;
    }

    /**
     * 设置店铺状态：如"正常营业"、"关店维修"
     */
    public function setStoreStatus(string $store_status): void
    {
        $this->store_status = $store_status;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deleted_at;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }
}
