<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CartEntity')]
class CartEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'merchant_id', description: '商户id', type: 'integer')]
    protected int $merchant_id;

    #[OA\Property(property: 'shop_id', description: '店铺ID', type: 'integer')]
    protected int $shop_id;

    #[OA\Property(property: 'user_id', description: '买家ID', type: 'integer')]
    protected int $user_id;

    #[OA\Property(property: 'product_id', description: '产品ID', type: 'integer')]
    protected int $product_id;

    #[OA\Property(property: 'quantity', description: '商品数量', type: 'integer')]
    protected int $quantity;

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
     * 获取店铺ID
     */
    public function getShopId(): int
    {
        return $this->shop_id;
    }

    /**
     * 设置店铺ID
     */
    public function setShopId(int $shop_id): void
    {
        $this->shop_id = $shop_id;
    }

    /**
     * 获取买家ID
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * 设置买家ID
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * 获取产品ID
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * 设置产品ID
     */
    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
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
