<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Contract;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerContractResponse')]
class ContractResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '合同ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'contract_no', description: '合同编号', type: 'string')]
    private string $contractNo;

    #[OA\Property(property: 'title', description: '合同标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'type', description: '合同类型:1入驻合同,2服务协议', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'status', description: '状态:0待签署,1已签署,2已过期', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'signed_at', description: '签署时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $signedAt;

    #[OA\Property(property: 'expired_at', description: '过期时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $expiredAt;

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

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getSignedAt(): ?string
    {
        return $this->signedAt;
    }

    public function setSignedAt(?string $signedAt): void
    {
        $this->signedAt = $signedAt;
    }

    public function getExpiredAt(): ?string
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?string $expiredAt): void
    {
        $this->expiredAt = $expiredAt;
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
