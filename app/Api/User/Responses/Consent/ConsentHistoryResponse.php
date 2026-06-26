<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Consent;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ConsentHistoryResponse')]
class ConsentHistoryResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '记录ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '同意类型:marketing,analytics,third_party', type: 'string')]
    private string $type;

    #[OA\Property(property: 'consented', description: '是否同意:0否，1是', type: 'integer')]
    private int $consented;

    #[OA\Property(property: 'ip_address', description: 'IP地址', type: 'string', nullable: true)]
    private ?string $ipAddress;

    #[OA\Property(property: 'user_agent', description: '用户代理', type: 'string', nullable: true)]
    private ?string $userAgent;

    #[OA\Property(property: 'created_at', description: '记录时间', type: 'string', format: 'date-time')]
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

    public function getConsented(): int
    {
        return $this->consented;
    }

    public function setConsented(int $consented): void
    {
        $this->consented = $consented;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): void
    {
        $this->userAgent = $userAgent;
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
