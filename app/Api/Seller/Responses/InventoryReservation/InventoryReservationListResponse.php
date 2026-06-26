<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\InventoryReservation;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerInventoryReservationListResponse')]
class InventoryReservationListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '预留ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'inventory_id', description: '库存ID', type: 'integer')]
    private int $inventoryId;

    #[OA\Property(property: 'order_id', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'quantity', description: '预留数量', type: 'integer')]
    private int $quantity;

    #[OA\Property(property: 'reserved_at', description: '预留时间', type: 'string', format: 'date-time')]
    private string $reservedAt;

    #[OA\Property(property: 'expires_at', description: '过期时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $expiresAt;

    #[OA\Property(property: 'status', description: '状态:0已释放,1预留中', type: 'integer')]
    private int $status;

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

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getReservedAt(): string
    {
        return $this->reservedAt;
    }

    public function setReservedAt(string $reservedAt): void
    {
        $this->reservedAt = $reservedAt;
    }

    public function getExpiresAt(): ?string
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?string $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
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
