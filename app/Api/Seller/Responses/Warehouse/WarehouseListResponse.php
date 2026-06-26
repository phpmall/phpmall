<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Warehouse;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerWarehouseListResponse')]
class WarehouseListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '仓库ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '仓库名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'code', description: '仓库编码', type: 'string')]
    private string $code;

    #[OA\Property(property: 'address', description: '仓库地址', type: 'string', nullable: true)]
    private ?string $address;

    #[OA\Property(property: 'contact_name', description: '联系人', type: 'string', nullable: true)]
    private ?string $contactName;

    #[OA\Property(property: 'contact_phone', description: '联系电话', type: 'string', nullable: true)]
    private ?string $contactPhone;

    #[OA\Property(property: 'status', description: '状态:0禁用,1启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'is_default', description: '是否默认仓库:0否,1是', type: 'integer')]
    private int $isDefault;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function setContactName(?string $contactName): void
    {
        $this->contactName = $contactName;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): void
    {
        $this->contactPhone = $contactPhone;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getIsDefault(): int
    {
        return $this->isDefault;
    }

    public function setIsDefault(int $isDefault): void
    {
        $this->isDefault = $isDefault;
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
