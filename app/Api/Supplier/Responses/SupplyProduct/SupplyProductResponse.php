<?php

declare(strict_types=1);

namespace App\Api\Supplier\Responses\SupplyProduct;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierSupplyProductResponse')]
class SupplyProductResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '商品ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '商品名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'category_id', description: '商品分类ID', type: 'integer')]
    private int $categoryId;

    #[OA\Property(property: 'category_name', description: '商品分类名称', type: 'string', nullable: true)]
    private ?string $categoryName;

    #[OA\Property(property: 'description', description: '商品描述', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'price', description: '供应单价(分)', type: 'integer')]
    private int $price;

    #[OA\Property(property: 'unit', description: '计量单位', type: 'string')]
    private string $unit;

    #[OA\Property(property: 'min_order_quantity', description: '最小起订量', type: 'integer', nullable: true)]
    private ?int $minOrderQuantity;

    #[OA\Property(property: 'stock', description: '库存数量', type: 'integer', nullable: true)]
    private ?int $stock;

    #[OA\Property(property: 'images', description: '商品图片列表', type: 'array', items: new OA\Items(type: 'string'), nullable: true)]
    private ?array $images;

    #[OA\Property(property: 'status', description: '状态:0下架,1上架', type: 'integer')]
    private int $status;

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

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(?string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    public function getMinOrderQuantity(): ?int
    {
        return $this->minOrderQuantity;
    }

    public function setMinOrderQuantity(?int $minOrderQuantity): void
    {
        $this->minOrderQuantity = $minOrderQuantity;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): void
    {
        $this->stock = $stock;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): void
    {
        $this->images = $images;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
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
