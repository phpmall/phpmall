<?php

declare(strict_types=1);

namespace App\Api\Supplier\Responses\Contract;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierContractResponse')]
class ContractResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '合同ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'contract_no', description: '合同编号', type: 'string')]
    private string $contractNo;

    #[OA\Property(property: 'title', description: '合同标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'type', description: '合同类型', type: 'string')]
    private string $type;

    #[OA\Property(property: 'content', description: '合同内容', type: 'string', nullable: true)]
    private ?string $content;

    #[OA\Property(property: 'supplier_id', description: '供应商ID', type: 'integer')]
    private int $supplierId;

    #[OA\Property(property: 'sign_status', description: '签署状态:0未签署,1已签署', type: 'integer')]
    private int $signStatus;

    #[OA\Property(property: 'signed_at', description: '签署时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $signedAt;

    #[OA\Property(property: 'effective_at', description: '生效时间', type: 'string', format: 'date-time')]
    private string $effectiveAt;

    #[OA\Property(property: 'expire_at', description: '到期时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $expireAt;

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

    public function getContractNo(): string
    {
        return $this->contractNo;
    }

    public function setContractNo(string $contractNo): void
    {
        $this->contractNo = $contractNo;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getSupplierId(): int
    {
        return $this->supplierId;
    }

    public function setSupplierId(int $supplierId): void
    {
        $this->supplierId = $supplierId;
    }

    public function getSignStatus(): int
    {
        return $this->signStatus;
    }

    public function setSignStatus(int $signStatus): void
    {
        $this->signStatus = $signStatus;
    }

    public function getSignedAt(): ?string
    {
        return $this->signedAt;
    }

    public function setSignedAt(?string $signedAt): void
    {
        $this->signedAt = $signedAt;
    }

    public function getEffectiveAt(): string
    {
        return $this->effectiveAt;
    }

    public function setEffectiveAt(string $effectiveAt): void
    {
        $this->effectiveAt = $effectiveAt;
    }

    public function getExpireAt(): ?string
    {
        return $this->expireAt;
    }

    public function setExpireAt(?string $expireAt): void
    {
        $this->expireAt = $expireAt;
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
