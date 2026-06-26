<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Product;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerProductResponse')]
class ProductResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '商品ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '商品名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'description', description: '商品描述', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'category_id', description: '商品分类ID', type: 'integer')]
    private int $categoryId;

    #[OA\Property(property: 'brand_id', description: '品牌ID', type: 'integer', nullable: true)]
    private ?int $brandId;

    #[OA\Property(property: 'shop_category_id', description: '店铺分类ID', type: 'integer', nullable: true)]
    private ?int $shopCategoryId;

    #[OA\Property(property: 'price', description: '销售价(分)', type: 'integer')]
    private int $price;

    #[OA\Property(property: 'market_price', description: '市场价(分)', type: 'integer', nullable: true)]
    private ?int $marketPrice;

    #[OA\Property(property: 'cost_price', description: '成本价(分)', type: 'integer', nullable: true)]
    private ?int $costPrice;

    #[OA\Property(property: 'stock', description: '库存数量', type: 'integer')]
    private int $stock;

    #[OA\Property(property: 'status', description: '商品状态:0下架,1上架', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'images', description: '商品图片列表', type: 'array', items: new OA\Items(type: 'string'))]
    private array $images;

    #[OA\Property(
        property: 'attributes',
        description: '商品属性列表',
        type: 'array',
        items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'id', type: 'integer', description: '属性ID'),
            new OA\Property(property: 'name', type: 'string', description: '属性名称'),
            new OA\Property(property: 'values', type: 'array', items: new OA\Items(type: 'string'), description: '属性可选值'),
        ])
    )]
    private array $attributes;

    #[OA\Property(
        property: 'skus',
        description: 'SKU列表',
        type: 'array',
        items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'id', type: 'integer', description: 'SKU ID'),
            new OA\Property(property: 'sku_code', type: 'string', description: 'SKU编码'),
            new OA\Property(property: 'price', type: 'integer', description: '售价(分)'),
            new OA\Property(property: 'stock', type: 'integer', description: '库存数量'),
            new OA\Property(property: 'image', type: 'string', description: 'SKU图片', nullable: true),
        ])
    )]
    private array $skus;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getBrandId(): ?int
    {
        return $this->brandId;
    }

    public function setBrandId(?int $brandId): void
    {
        $this->brandId = $brandId;
    }

    public function getShopCategoryId(): ?int
    {
        return $this->shopCategoryId;
    }

    public function setShopCategoryId(?int $shopCategoryId): void
    {
        $this->shopCategoryId = $shopCategoryId;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getMarketPrice(): ?int
    {
        return $this->marketPrice;
    }

    public function setMarketPrice(?int $marketPrice): void
    {
        $this->marketPrice = $marketPrice;
    }

    public function getCostPrice(): ?int
    {
        return $this->costPrice;
    }

    public function setCostPrice(?int $costPrice): void
    {
        $this->costPrice = $costPrice;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getSkus(): array
    {
        return $this->skus;
    }

    public function setSkus(array $skus): void
    {
        $this->skus = $skus;
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
