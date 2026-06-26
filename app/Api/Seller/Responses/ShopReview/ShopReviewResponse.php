<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\ShopReview;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerShopReviewResponse')]
class ShopReviewResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '评价ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shop_id', description: '店铺ID', type: 'integer')]
    private int $shopId;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'user_nickname', description: '用户昵称', type: 'string')]
    private string $userNickname;

    #[OA\Property(property: 'user_avatar', description: '用户头像', type: 'string', nullable: true)]
    private ?string $userAvatar;

    #[OA\Property(property: 'order_id', description: '订单ID', type: 'integer', nullable: true)]
    private ?int $orderId;

    #[OA\Property(property: 'rating', description: '评分:1-5', type: 'integer')]
    private int $rating;

    #[OA\Property(property: 'content', description: '评价内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'images', description: '评价图片', type: 'array', items: new OA\Items(type: 'string'))]
    private array $images;

    #[OA\Property(property: 'reply_content', description: '商家回复内容', type: 'string', nullable: true)]
    private ?string $replyContent;

    #[OA\Property(property: 'reply_at', description: '商家回复时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $replyAt;

    #[OA\Property(property: 'is_anonymous', description: '是否匿名:0否,1是', type: 'integer')]
    private int $isAnonymous;

    #[OA\Property(property: 'status', description: '状态:0待审核,1已通过,2已拒绝', type: 'integer')]
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

    public function getUserNickname(): string
    {
        return $this->userNickname;
    }

    public function setUserNickname(string $userNickname): void
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

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): void
    {
        $this->orderId = $orderId;
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

    public function getIsAnonymous(): int
    {
        return $this->isAnonymous;
    }

    public function setIsAnonymous(int $isAnonymous): void
    {
        $this->isAnonymous = $isAnonymous;
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
