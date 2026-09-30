<?php

declare(strict_types=1);

namespace App\Domains\Cart\Responses\Cart;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CartResponse')]
class CartResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'merchantId', description: '商家ID', type: 'integer')]
    private int $merchantId;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'skuId', description: 'SKU ID', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'quantity', description: '数量', type: 'integer')]
    private int $quantity;

    #[OA\Property(property: 'isSelected', description: '是否选中：0-未选中，1-已选中', type: 'integer')]
    private int $isSelected;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

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
     * 获取数量
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * 设置数量
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * 获取是否选中：0-未选中，1-已选中
     */
    public function getIsSelected(): int
    {
        return $this->isSelected;
    }

    /**
     * 设置是否选中：0-未选中，1-已选中
     */
    public function setIsSelected(int $isSelected): void
    {
        $this->isSelected = $isSelected;
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
}
