<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Product;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalProductResponse')]
class ProductResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '商品ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '商品名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'subtitle', description: '商品副标题', type: 'string', nullable: true)]
    private ?string $subtitle;

    #[OA\Property(property: 'description', description: '商品描述', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'main_image', description: '主图', type: 'string')]
    private string $mainImage;

    #[OA\Property(property: 'images', description: '商品图片列表', type: 'array', items: new OA\Items(type: 'string'))]
    private array $images;

    #[OA\Property(property: 'category_id', description: '分类ID', type: 'integer')]
    private int $categoryId;

    #[OA\Property(property: 'category_name', description: '分类名称', type: 'string', nullable: true)]
    private ?string $categoryName;

    #[OA\Property(property: 'shop_id', description: '店铺ID', type: 'integer')]
    private int $shopId;

    #[OA\Property(property: 'shop_name', description: '店铺名称', type: 'string', nullable: true)]
    private ?string $shopName;

    #[OA\Property(property: 'price', description: '销售价(分)', type: 'integer')]
    private int $price;

    #[OA\Property(property: 'market_price', description: '市场价(分)', type: 'integer', nullable: true)]
    private ?int $marketPrice;

    #[OA\Property(property: 'stock', description: '库存数量', type: 'integer')]
    private int $stock;

    #[OA\Property(property: 'sold_count', description: '已售数量', type: 'integer')]
    private int $soldCount;

    #[OA\Property(property: 'rating', description: '评分', type: 'number', format: 'float', nullable: true)]
    private ?float $rating;

    #[OA\Property(property: 'review_count', description: '评价数量', type: 'integer')]
    private int $reviewCount;

    #[OA\Property(property: 'is_hot', description: '是否热销:0否,1是', type: 'integer')]
    private int $isHot;

    #[OA\Property(property: 'is_recommend', description: '是否推荐:0否,1是', type: 'integer')]
    private int $isRecommend;

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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getMainImage(): string
    {
        return $this->mainImage;
    }

    public function setMainImage(string $mainImage): void
    {
        $this->mainImage = $mainImage;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
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

    public function getShopId(): int
    {
        return $this->shopId;
    }

    public function setShopId(int $shopId): void
    {
        $this->shopId = $shopId;
    }

    public function getShopName(): ?string
    {
        return $this->shopName;
    }

    public function setShopName(?string $shopName): void
    {
        $this->shopName = $shopName;
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

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getSoldCount(): int
    {
        return $this->soldCount;
    }

    public function setSoldCount(int $soldCount): void
    {
        $this->soldCount = $soldCount;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): void
    {
        $this->rating = $rating;
    }

    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }

    public function setReviewCount(int $reviewCount): void
    {
        $this->reviewCount = $reviewCount;
    }

    public function getIsHot(): int
    {
        return $this->isHot;
    }

    public function setIsHot(int $isHot): void
    {
        $this->isHot = $isHot;
    }

    public function getIsRecommend(): int
    {
        return $this->isRecommend;
    }

    public function setIsRecommend(int $isRecommend): void
    {
        $this->isRecommend = $isRecommend;
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
