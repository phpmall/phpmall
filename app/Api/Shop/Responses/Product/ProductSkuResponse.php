<?php

declare(strict_types=1);

namespace App\Api\Shop\Responses\Product;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopProductSkuResponse')]
class ProductSkuResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'SKU ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'product_id', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'sku_code', description: 'SKU编码', type: 'string')]
    private string $skuCode;

    #[OA\Property(property: 'price', description: '售价(分)', type: 'integer')]
    private int $price;

    #[OA\Property(property: 'stock', description: '库存数量', type: 'integer')]
    private int $stock;

    #[OA\Property(
        property: 'attributes',
        description: 'SKU属性值',
        type: 'array',
        items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'attribute_id', type: 'integer', description: '属性ID'),
            new OA\Property(property: 'attribute_name', type: 'string', description: '属性名称'),
            new OA\Property(property: 'value', type: 'string', description: '属性值'),
        ])
    )]
    private array $attributes;

    #[OA\Property(property: 'image', description: 'SKU图片', type: 'string', nullable: true)]
    private ?string $image;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string', format: 'date-time')]
    private string $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getSkuCode(): string
    {
        return $this->skuCode;
    }

    public function setSkuCode(string $skuCode): void
    {
        $this->skuCode = $skuCode;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
