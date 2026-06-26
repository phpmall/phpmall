<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Contract;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContractSignResponse')]
class ContractSignResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'contract_id', description: '合同ID', type: 'integer')]
    private int $contractId;

    #[OA\Property(property: 'signed_at', description: '签署时间', type: 'string', format: 'date-time')]
    private string $signedAt;

    #[OA\Property(property: 'signature_id', description: '签名ID', type: 'string')]
    private string $signatureId;

    #[OA\Property(property: 'download_url', description: '合同下载链接', type: 'string', nullable: true)]
    private ?string $downloadUrl;

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

    public function getSignatureId(): string
    {
        return $this->signatureId;
    }

    public function setSignatureId(string $signatureId): void
    {
        $this->signatureId = $signatureId;
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
