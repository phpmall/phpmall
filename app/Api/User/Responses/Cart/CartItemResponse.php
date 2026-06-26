<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Cart;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CartItemResponse')]
class CartItemResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '购物车ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'sku_id', description: 'SKU ID', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'product_id', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'product_name', description: '商品名称', type: 'string')]
    private string $productName;

    #[OA\Property(property: 'sku_name', description: 'SKU规格名称', type: 'string', nullable: true)]
    private ?string $skuName;

    #[OA\Property(property: 'image', description: '商品图片', type: 'string', nullable: true)]
    private ?string $image;

    #[OA\Property(property: 'price', description: '单价(分)', type: 'integer')]
    private int $price;

    #[OA\Property(property: 'quantity', description: '购买数量', type: 'integer')]
    private int $quantity;

    #[OA\Property(property: 'total_price', description: '小计(分)', type: 'integer')]
    private int $totalPrice;

    #[OA\Property(property: 'stock', description: '库存数量', type: 'integer')]
    private int $stock;

    #[OA\Property(property: 'is_selected', description: '是否选中:0否，1是', type: 'integer')]
    private int $isSelected;

    #[OA\Property(property: 'is_valid', description: '是否有效:0否，1是', type: 'integer')]
    private int $isValid;

    #[OA\Property(property: 'invalid_reason', description: '无效原因', type: 'string', nullable: true)]
    private ?string $invalidReason;

    #[OA\Property(property: 'created_at', description: '加入时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSkuId(): int
    {
        return $this->skuId;
    }

    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    public function getSkuName(): ?string
    {
        return $this->skuName;
    }

    public function setSkuName(?string $skuName): void
    {
        $this->skuName = $skuName;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getIsSelected(): int
    {
        return $this->isSelected;
    }

    public function setIsSelected(int $isSelected): void
    {
        $this->isSelected = $isSelected;
    }

    public function getIsValid(): int
    {
        return $this->isValid;
    }

    public function setIsValid(int $isValid): void
    {
        $this->isValid = $isValid;
    }

    public function getInvalidReason(): ?string
    {
        return $this->invalidReason;
    }

    public function setInvalidReason(?string $invalidReason): void
    {
        $this->invalidReason = $invalidReason;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
