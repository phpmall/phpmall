<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\ProductAudit;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerProductAuditResponse')]
class ProductAuditResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '审核ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'product_id', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'product_name', description: '商品名称', type: 'string')]
    private string $productName;

    #[OA\Property(property: 'status', description: '审核状态:0待审核,1通过,2拒绝', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'reason', description: '审核拒绝原因', type: 'string', nullable: true)]
    private ?string $reason;

    #[OA\Property(property: 'auditor_id', description: '审核人ID', type: 'integer', nullable: true)]
    private ?int $auditorId;

    #[OA\Property(property: 'auditor_name', description: '审核人名称', type: 'string', nullable: true)]
    private ?string $auditorName;

    #[OA\Property(property: 'audited_at', description: '审核时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $auditedAt;

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

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    public function getAuditorId(): ?int
    {
        return $this->auditorId;
    }

    public function setAuditorId(?int $auditorId): void
    {
        $this->auditorId = $auditorId;
    }

    public function getAuditorName(): ?string
    {
        return $this->auditorName;
    }

    public function setAuditorName(?string $auditorName): void
    {
        $this->auditorName = $auditorName;
    }

    public function getAuditedAt(): ?string
    {
        return $this->auditedAt;
    }

    public function setAuditedAt(?string $auditedAt): void
    {
        $this->auditedAt = $auditedAt;
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
