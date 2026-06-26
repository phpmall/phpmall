<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Contract;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerContractDownloadResponse')]
class ContractDownloadResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'download_url', description: '下载链接', type: 'string')]
    private string $downloadUrl;

    #[OA\Property(property: 'file_name', description: '文件名', type: 'string')]
    private string $fileName;

    #[OA\Property(property: 'expires_at', description: '链接过期时间', type: 'string', format: 'date-time')]
    private string $expiresAt;

    public function getDownloadUrl(): string
    {
        return $this->downloadUrl;
    }

    public function setDownloadUrl(string $downloadUrl): void
    {
        $this->downloadUrl = $downloadUrl;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getExpiresAt(): string
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(string $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }
}
