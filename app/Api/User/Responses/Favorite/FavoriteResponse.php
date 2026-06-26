<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Favorite;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'FavoriteResponse')]
class FavoriteResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '收藏ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '收藏类型:product,shop,article', type: 'string')]
    private string $type;

    #[OA\Property(property: 'target_id', description: '目标ID', type: 'integer')]
    private int $targetId;

    #[OA\Property(property: 'target_name', description: '目标名称', type: 'string')]
    private string $targetName;

    #[OA\Property(property: 'target_image', description: '目标图片', type: 'string', nullable: true)]
    private ?string $targetImage;

    #[OA\Property(property: 'target_price', description: '目标价格(分)', type: 'integer', nullable: true)]
    private ?int $targetPrice;

    #[OA\Property(property: 'created_at', description: '收藏时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getTargetId(): int
    {
        return $this->targetId;
    }

    public function setTargetId(int $targetId): void
    {
        $this->targetId = $targetId;
    }

    public function getTargetName(): string
    {
        return $this->targetName;
    }

    public function setTargetName(string $targetName): void
    {
        $this->targetName = $targetName;
    }

    public function getTargetImage(): ?string
    {
        return $this->targetImage;
    }

    public function setTargetImage(?string $targetImage): void
    {
        $this->targetImage = $targetImage;
    }

    public function getTargetPrice(): ?int
    {
        return $this->targetPrice;
    }

    public function setTargetPrice(?int $targetPrice): void
    {
        $this->targetPrice = $targetPrice;
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
