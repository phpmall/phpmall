<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'StoreEntity')]
class StoreEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'merchant_id', description: '商户id', type: 'integer')]
    protected int $merchant_id;

    #[OA\Property(property: 'store_logo', description: '店铺LOGO', type: 'string')]
    protected string $store_logo;

    #[OA\Property(property: 'store_introduce', description: '店铺简介', type: 'string')]
    protected string $store_introduce;

    #[OA\Property(property: 'store_background', description: '店铺背景图', type: 'string')]
    protected string $store_background;

    #[OA\Property(property: 'store_category', description: '店铺所属类别', type: 'string')]
    protected string $store_category;

    #[OA\Property(property: 'store_rating', description: '店铺评分：一般取值范围在0~5之间', type: 'string')]
    protected string $store_rating;

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
     * 获取店铺LOGO
     */
    public function getStoreLogo(): string
    {
        return $this->store_logo;
    }

    /**
     * 设置店铺LOGO
     */
    public function setStoreLogo(string $store_logo): void
    {
        $this->store_logo = $store_logo;
    }

    /**
     * 获取店铺简介
     */
    public function getStoreIntroduce(): string
    {
        return $this->store_introduce;
    }

    /**
     * 设置店铺简介
     */
    public function setStoreIntroduce(string $store_introduce): void
    {
        $this->store_introduce = $store_introduce;
    }

    /**
     * 获取店铺背景图
     */
    public function getStoreBackground(): string
    {
        return $this->store_background;
    }

    /**
     * 设置店铺背景图
     */
    public function setStoreBackground(string $store_background): void
    {
        $this->store_background = $store_background;
    }

    /**
     * 获取店铺所属类别
     */
    public function getStoreCategory(): string
    {
        return $this->store_category;
    }

    /**
     * 设置店铺所属类别
     */
    public function setStoreCategory(string $store_category): void
    {
        $this->store_category = $store_category;
    }

    /**
     * 获取店铺评分：一般取值范围在0~5之间
     */
    public function getStoreRating(): string
    {
        return $this->store_rating;
    }

    /**
     * 设置店铺评分：一般取值范围在0~5之间
     */
    public function setStoreRating(string $store_rating): void
    {
        $this->store_rating = $store_rating;
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
