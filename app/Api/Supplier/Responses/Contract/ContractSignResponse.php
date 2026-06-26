<?php

declare(strict_types=1);

namespace App\Api\Supplier\Responses\Contract;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierContractSignResponse')]
class ContractSignResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'success', description: '签署结果', type: 'boolean')]
    private bool $success;

    #[OA\Property(property: 'contract_id', description: '合同ID', type: 'integer')]
    private int $contractId;

    #[OA\Property(property: 'signed_at', description: '签署时间', type: 'string', format: 'date-time')]
    private string $signedAt;

    #[OA\Property(property: 'download_url', description: '合同下载地址', type: 'string', nullable: true)]
    private ?string $downloadUrl;

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function getContractId(): int
    {
        return $this->contractId;
    }

    public function setContractId(int $contractId): void
    {
        $this->contractId = $contractId;
    }

    public function getSignedAt(): string
    {
        return $this->signedAt;
    }

    public function setSignedAt(string $signedAt): void
    {
        $this->signedAt = $signedAt;
    }

    public function getDownloadUrl(): ?string
    {
        return $this->downloadUrl;
    }

    public function setDownloadUrl(?string $downloadUrl): void
    {
        $this->downloadUrl = $downloadUrl;
    }
}
