<?php

declare(strict_types=1);

namespace App\Api\Common\Responses\Agreement;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommonAgreementResponse')]
class AgreementResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '协议ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '协议类型', type: 'string')]
    private string $type;

    #[OA\Property(property: 'title', description: '协议标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '协议内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'version', description: '协议版本', type: 'string')]
    private string $version;

    #[OA\Property(property: 'is_required', description: '是否必须同意:0否,1是', type: 'integer')]
    private int $isRequired;

    #[OA\Property(property: 'effective_at', description: '生效时间', type: 'string', format: 'date-time')]
    private string $effectiveAt;

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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getIsRequired(): int
    {
        return $this->isRequired;
    }

    public function setIsRequired(int $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    public function getEffectiveAt(): string
    {
        return $this->effectiveAt;
    }

    public function setEffectiveAt(string $effectiveAt): void
    {
        $this->effectiveAt = $effectiveAt;
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
