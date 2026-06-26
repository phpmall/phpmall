<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Brand;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerBrandListResponse')]
class BrandListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '品牌ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '品牌名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'logo', description: '品牌Logo', type: 'string', nullable: true)]
    private ?string $logo;

    #[OA\Property(property: 'description', description: '品牌描述', type: 'string', nullable: true)]
    private ?string $description;

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
