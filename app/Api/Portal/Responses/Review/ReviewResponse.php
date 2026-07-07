<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Review;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalReviewResponse')]
class ReviewResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '评价ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'product_id', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'product_name', description: '商品名称', type: 'string', nullable: true)]
    private ?string $productName;

    #[OA\Property(property: 'shop_id', description: '店铺ID', type: 'integer')]
    private int $shopId;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'user_nickname', description: '用户昵称', type: 'string', nullable: true)]
    private ?string $userNickname;

    #[OA\Property(property: 'user_avatar', description: '用户头像', type: 'string', nullable: true)]
    private ?string $userAvatar;

    #[OA\Property(property: 'rating', description: '评分:1-5', type: 'integer')]
    private int $rating;

    #[OA\Property(property: 'content', description: '评价内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'images', description: '评价图片', type: 'array', items: new OA\Items(type: 'string'))]
    private array $images;

    #[OA\Property(property: 'reply', description: '商家回复', type: 'string', nullable: true)]
    private ?string $reply;

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

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): void
    {
        $this->productName = $productName;
    }

    public function getShopId(): int
    {
        return $this->shopId;
    }

    public function setShopId(int $shopId): void
    {
        $this->shopId = $shopId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserNickname(): ?string
    {
        return $this->userNickname;
    }

    public function setUserNickname(?string $userNickname): void
    {
        $this->userNickname = $userNickname;
    }

    public function getUserAvatar(): ?string
    {
        return $this->userAvatar;
    }

    public function setUserAvatar(?string $userAvatar): void
    {
        $this->userAvatar = $userAvatar;
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

    public function getReply(): ?string
    {
        return $this->reply;
    }

    public function setReply(?string $reply): void
    {
        $this->reply = $reply;
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
