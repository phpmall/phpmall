<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerEntity')]
class SellerEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'merchant_id', description: '商户id', type: 'integer')]
    protected int $merchant_id;

    #[OA\Property(property: 'shop_id', description: '店铺id', type: 'integer')]
    protected int $shop_id;

    #[OA\Property(property: 'store_id', description: '门店ID', type: 'integer')]
    protected int $store_id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $user_id;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'string')]
    protected string $status;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

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
     * 获取店铺id
     */
    public function getShopId(): int
    {
        return $this->shop_id;
    }

    /**
     * 设置店铺id
     */
    public function setShopId(int $shop_id): void
    {
        $this->shop_id = $shop_id;
    }

    /**
     * 获取门店ID
     */
    public function getStoreId(): int
    {
        return $this->store_id;
    }

    /**
     * 设置门店ID
     */
    public function setStoreId(int $store_id): void
    {
        $this->store_id = $store_id;
    }

    /**
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
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
}
