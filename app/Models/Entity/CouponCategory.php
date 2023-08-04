<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Focite\Builder\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CouponCategorySchema')]
class CouponCategory
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'seller_id', description: '卖家id', type: 'int')]
    protected int $sellerId;

    #[OA\Property(property: 'shop_id', description: '店铺id', type: 'int')]
    protected int $shopId;

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
     * 获取卖家id
     */
    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    /**
     * 设置卖家id
     */
    public function setSellerId(int $sellerId): void
    {
        $this->sellerId = $sellerId;
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
