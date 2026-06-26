<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\InventoryTransaction;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerInventoryTransactionListResponse')]
class InventoryTransactionListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '流水ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'inventory_id', description: '库存ID', type: 'integer')]
    private int $inventoryId;

    #[OA\Property(property: 'type', description: '流水类型', type: 'string')]
    private string $type;

    #[OA\Property(property: 'quantity', description: '变动数量', type: 'integer')]
    private int $quantity;

    #[OA\Property(property: 'before_quantity', description: '变动前数量', type: 'integer')]
    private int $beforeQuantity;

    #[OA\Property(property: 'after_quantity', description: '变动后数量', type: 'integer')]
    private int $afterQuantity;

    #[OA\Property(property: 'reference_type', description: '关联类型', type: 'string', nullable: true)]
    private ?string $referenceType;

    #[OA\Property(property: 'reference_id', description: '关联ID', type: 'integer', nullable: true)]
    private ?int $referenceId;

    #[OA\Property(property: 'operator_id', description: '操作人ID', type: 'integer', nullable: true)]
    private ?int $operatorId;

    #[OA\Property(property: 'remark', description: '备注', type: 'string', nullable: true)]
    private ?string $remark;

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

    public function getInventoryId(): int
    {
        return $this->inventoryId;
    }

    public function setInventoryId(int $inventoryId): void
    {
        $this->inventoryId = $inventoryId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getBeforeQuantity(): int
    {
        return $this->beforeQuantity;
    }

    public function setBeforeQuantity(int $beforeQuantity): void
    {
        $this->beforeQuantity = $beforeQuantity;
    }

    public function getAfterQuantity(): int
    {
        return $this->afterQuantity;
    }

    public function setAfterQuantity(int $afterQuantity): void
    {
        $this->afterQuantity = $afterQuantity;
    }

    public function getReferenceType(): ?string
    {
        return $this->referenceType;
    }

    public function setReferenceType(?string $referenceType): void
    {
        $this->referenceType = $referenceType;
    }

    public function getReferenceId(): ?int
    {
        return $this->referenceId;
    }

    public function setReferenceId(?int $referenceId): void
    {
        $this->referenceId = $referenceId;
    }

    public function getOperatorId(): ?int
    {
        return $this->operatorId;
    }

    public function setOperatorId(?int $operatorId): void
    {
        $this->operatorId = $operatorId;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
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
