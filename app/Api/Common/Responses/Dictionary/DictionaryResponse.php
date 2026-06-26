<?php

declare(strict_types=1);

namespace App\Api\Common\Responses\Dictionary;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommonDictionaryResponse')]
class DictionaryResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '字典ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '字典类型', type: 'string')]
    private string $type;

    #[OA\Property(property: 'label', description: '字典标签', type: 'string')]
    private string $label;

    #[OA\Property(property: 'value', description: '字典值', type: 'string')]
    private string $value;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'description', description: '字典说明', type: 'string', nullable: true)]
    private ?string $description;

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

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
