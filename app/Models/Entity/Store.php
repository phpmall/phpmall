<?php

declare(strict_types=1);

namespace App\Models\Entity;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'StoreSchema')]
class Store
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'seller_id', description: '卖家ID', type: 'int')]
    protected int $sellerId;

    #[OA\Property(property: 'store_logo', description: '店铺LOGO', type: 'string')]
    protected string $storeLogo;

    #[OA\Property(property: 'store_introduce', description: '店铺简介', type: 'string')]
    protected string $storeIntroduce;

    #[OA\Property(property: 'store_background', description: '店铺背景图', type: 'string')]
    protected string $storeBackground;

    #[OA\Property(property: 'store_category', description: '店铺所属类别', type: 'string')]
    protected string $storeCategory;

    #[OA\Property(property: 'store_rating', description: '店铺评分：一般取值范围在0~5之间', type: 'string')]
    protected string $storeRating;

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
     * 获取店铺LOGO
     */
    public function getStoreLogo(): string
    {
        return $this->storeLogo;
    }

    /**
     * 设置店铺LOGO
     */
    public function setStoreLogo(string $storeLogo): void
    {
        $this->storeLogo = $storeLogo;
    }

    /**
     * 获取店铺简介
     */
    public function getStoreIntroduce(): string
    {
        return $this->storeIntroduce;
    }

    /**
     * 设置店铺简介
     */
    public function setStoreIntroduce(string $storeIntroduce): void
    {
        $this->storeIntroduce = $storeIntroduce;
    }

    /**
     * 获取店铺背景图
     */
    public function getStoreBackground(): string
    {
        return $this->storeBackground;
    }

    /**
     * 设置店铺背景图
     */
    public function setStoreBackground(string $storeBackground): void
    {
        $this->storeBackground = $storeBackground;
    }

    /**
     * 获取店铺所属类别
     */
    public function getStoreCategory(): string
    {
        return $this->storeCategory;
    }

    /**
     * 设置店铺所属类别
     */
    public function setStoreCategory(string $storeCategory): void
    {
        $this->storeCategory = $storeCategory;
    }

    /**
     * 获取店铺评分：一般取值范围在0~5之间
     */
    public function getStoreRating(): string
    {
        return $this->storeRating;
    }

    /**
     * 设置店铺评分：一般取值范围在0~5之间
     */
    public function setStoreRating(string $storeRating): void
    {
        $this->storeRating = $storeRating;
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
