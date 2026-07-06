<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Region;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalRegionResponse')]
class RegionResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '地区ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentCode', description: '父级地区编码', type: 'string')]
    private string $parentCode;

    #[OA\Property(property: 'name', description: '地区名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'code', description: '地区编码', type: 'string')]
    private string $code;

    #[OA\Property(property: 'level', description: '地区层级:1省,2市,3区', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'zipCode', description: '邮编', type: 'string')]
    private string $zipCode;

    #[OA\Property(property: 'hasChildren', description: '是否有子级', type: 'boolean')]
    private bool $hasChildren;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getParentCode(): string
    {
        return $this->parentCode;
    }

    public function setParentCode(string $parentCode): void
    {
        $this->parentCode = $parentCode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getHasChildren(): bool
    {
        return $this->hasChildren;
    }

    public function setHasChildren(bool $hasChildren): void
    {
        $this->hasChildren = $hasChildren;
    }
}
