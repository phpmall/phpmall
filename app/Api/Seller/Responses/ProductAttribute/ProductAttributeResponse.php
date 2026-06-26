<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\ProductAttribute;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerProductAttributeResponse')]
class ProductAttributeResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '属性ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '属性名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'values', description: '属性可选值列表', type: 'array', items: new OA\Items(type: 'string'))]
    private array $values;

    #[OA\Property(property: 'sort', description: '排序值', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string', format: 'date-time')]
    private string $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
