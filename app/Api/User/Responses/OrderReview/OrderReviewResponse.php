<?php

declare(strict_types=1);

namespace App\Api\User\Responses\OrderReview;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderReviewResponse')]
class OrderReviewResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '评价ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'order_id', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'product_id', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'product_name', description: '商品名称', type: 'string')]
    private string $productName;

    #[OA\Property(property: 'rating', description: '评分:1-5', type: 'integer')]
    private int $rating;

    #[OA\Property(property: 'content', description: '评价内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'images', description: '评价图片', type: 'array', items: new OA\Items(type: 'string', format: 'uri'))]
    private array $images;

    #[OA\Property(property: 'is_anonymous', description: '是否匿名:0否，1是', type: 'integer')]
    private int $isAnonymous;

    #[OA\Property(property: 'reply_content', description: '商家回复', type: 'string', nullable: true)]
    private ?string $replyContent;

    #[OA\Property(property: 'reply_at', description: '回复时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $replyAt;

    #[OA\Property(property: 'created_at', description: '评价时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
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

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    public function getIsAnonymous(): int
    {
        return $this->isAnonymous;
    }

    public function setIsAnonymous(int $isAnonymous): void
    {
        $this->isAnonymous = $isAnonymous;
    }

    public function getReplyContent(): ?string
    {
        return $this->replyContent;
    }

    public function setReplyContent(?string $replyContent): void
    {
        $this->replyContent = $replyContent;
    }

    public function getReplyAt(): ?string
    {
        return $this->replyAt;
    }

    public function setReplyAt(?string $replyAt): void
    {
        $this->replyAt = $replyAt;
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
