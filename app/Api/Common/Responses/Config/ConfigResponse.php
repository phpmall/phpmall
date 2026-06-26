<?php

declare(strict_types=1);

namespace App\Api\Common\Responses\Config;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommonConfigResponse')]
class ConfigResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '配置ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'key', description: '配置键', type: 'string')]
    private string $key;

    #[OA\Property(property: 'value', description: '配置值', type: 'string')]
    private string $value;

    #[OA\Property(property: 'name', description: '配置名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'group', description: '配置分组', type: 'string')]
    private string $group;

    #[OA\Property(property: 'type', description: '配置类型', type: 'string')]
    private string $type;

    #[OA\Property(property: 'description', description: '配置说明', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function setGroup(string $group): void
    {
        $this->group = $group;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }
}
