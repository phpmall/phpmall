<?php

declare(strict_types=1);

namespace App\Domains\Product\Responses\ProductSku;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductSkuResponse')]
class ProductSkuResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'merchantId', description: '商家ID', type: 'integer')]
    private int $merchantId;

    #[OA\Property(property: 'skuCode', description: 'SKU编码', type: 'string')]
    private string $skuCode;

    #[OA\Property(property: 'skuSpecs', description: '规格组合', type: 'string')]
    private string $skuSpecs;

    #[OA\Property(property: 'price', description: '售价（分）', type: 'integer')]
    private int $price;

    #[OA\Property(property: 'marketPrice', description: '市场价（分）', type: 'integer')]
    private int $marketPrice;

    #[OA\Property(property: 'costPrice', description: '成本价（分）', type: 'integer')]
    private int $costPrice;

    #[OA\Property(property: 'stock', description: '库存', type: 'integer')]
    private int $stock;

    #[OA\Property(property: 'stockAlarm', description: '库存预警值', type: 'integer')]
    private int $stockAlarm;

    #[OA\Property(property: 'weight', description: '重量（克）', type: 'integer')]
    private int $weight;

    #[OA\Property(property: 'image', description: 'SKU独立图片', type: 'string')]
    private string $image;

    #[OA\Property(property: 'salesCount', description: 'SKU销量', type: 'integer')]
    private int $salesCount;

    #[OA\Property(property: 'status', description: '0=禁用 1=启用', type: 'integer')]
    private int $status;

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
     * 获取SKU编码
     */
    public function getSkuCode(): string
    {
        return $this->skuCode;
    }

    /**
     * 设置SKU编码
     */
    public function setSkuCode(string $skuCode): void
    {
        $this->skuCode = $skuCode;
    }

    /**
     * 获取规格组合
     */
    public function getSkuSpecs(): string
    {
        return $this->skuSpecs;
    }

    /**
     * 设置规格组合
     */
    public function setSkuSpecs(string $skuSpecs): void
    {
        $this->skuSpecs = $skuSpecs;
    }

    /**
     * 获取售价（分）
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * 设置售价（分）
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * 获取市场价（分）
     */
    public function getMarketPrice(): int
    {
        return $this->marketPrice;
    }

    /**
     * 设置市场价（分）
     */
    public function setMarketPrice(int $marketPrice): void
    {
        $this->marketPrice = $marketPrice;
    }

    /**
     * 获取成本价（分）
     */
    public function getCostPrice(): int
    {
        return $this->costPrice;
    }

    /**
     * 设置成本价（分）
     */
    public function setCostPrice(int $costPrice): void
    {
        $this->costPrice = $costPrice;
    }

    /**
     * 获取库存
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * 设置库存
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * 获取库存预警值
     */
    public function getStockAlarm(): int
    {
        return $this->stockAlarm;
    }

    /**
     * 设置库存预警值
     */
    public function setStockAlarm(int $stockAlarm): void
    {
        $this->stockAlarm = $stockAlarm;
    }

    /**
     * 获取重量（克）
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * 设置重量（克）
     */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * 获取SKU独立图片
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * 设置SKU独立图片
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * 获取SKU销量
     */
    public function getSalesCount(): int
    {
        return $this->salesCount;
    }

    /**
     * 设置SKU销量
     */
    public function setSalesCount(int $salesCount): void
    {
        $this->salesCount = $salesCount;
    }

    /**
     * 获取0=禁用 1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置0=禁用 1=启用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
