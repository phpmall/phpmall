<?php

declare(strict_types=1);

namespace App\Api\Shop\Responses\Shop;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopShopResponse')]
class ShopResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '店铺ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '店铺名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'logo', description: '店铺Logo', type: 'string', nullable: true)]
    private ?string $logo;

    #[OA\Property(property: 'description', description: '店铺描述', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'category_id', description: '店铺分类ID', type: 'integer', nullable: true)]
    private ?int $categoryId;

    #[OA\Property(property: 'category_name', description: '店铺分类名称', type: 'string', nullable: true)]
    private ?string $categoryName;

    #[OA\Property(property: 'rating', description: '店铺评分', type: 'number', format: 'float', nullable: true)]
    private ?float $rating;

    #[OA\Property(property: 'product_count', description: '商品数量', type: 'integer')]
    private int $productCount;

    #[OA\Property(property: 'sold_count', description: '累计销量', type: 'integer')]
    private int $soldCount;

    #[OA\Property(property: 'follow_count', description: '关注数量', type: 'integer')]
    private int $followCount;

    #[OA\Property(property: 'status', description: '状态:0关闭,1营业', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): void
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

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): void
    {
        $this->rating = $rating;
    }

    public function getProductCount(): int
    {
        return $this->productCount;
    }

    public function setProductCount(int $productCount): void
    {
        $this->productCount = $productCount;
    }

    public function getSoldCount(): int
    {
        return $this->soldCount;
    }

    public function setSoldCount(int $soldCount): void
    {
        $this->soldCount = $soldCount;
    }

    public function getFollowCount(): int
    {
        return $this->followCount;
    }

    public function setFollowCount(int $followCount): void
    {
        $this->followCount = $followCount;
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
}
