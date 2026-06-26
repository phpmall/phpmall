<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Contract;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContractResponse')]
class ContractResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '合同ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'title', description: '合同标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'type', description: '合同类型:user_agreement,privacy_policy,service_terms', type: 'string')]
    private string $type;

    #[OA\Property(property: 'version', description: '版本号', type: 'string')]
    private string $version;

    #[OA\Property(property: 'content', description: '合同内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'effective_at', description: '生效时间', type: 'string', format: 'date-time')]
    private string $effectiveAt;

    #[OA\Property(property: 'is_signed', description: '是否已签署:0否，1是', type: 'integer')]
    private int $isSigned;

    #[OA\Property(property: 'signed_at', description: '签署时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $signedAt;

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

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getEffectiveAt(): string
    {
        return $this->effectiveAt;
    }

    public function setEffectiveAt(string $effectiveAt): void
    {
        $this->effectiveAt = $effectiveAt;
    }

    public function getIsSigned(): int
    {
        return $this->isSigned;
    }

    public function setIsSigned(int $isSigned): void
    {
        $this->isSigned = $isSigned;
    }

    public function getSignedAt(): ?string
    {
        return $this->signedAt;
    }

    public function setSignedAt(?string $signedAt): void
    {
        $this->signedAt = $signedAt;
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
