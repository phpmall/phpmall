<?php

declare(strict_types=1);

namespace App\Api\Supplier\Responses\Inventory;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierInventoryResponse')]
class InventoryResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '库存ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'product_id', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'product_name', description: '商品名称', type: 'string', nullable: true)]
    private ?string $productName;

    #[OA\Property(property: 'sku_id', description: 'SKU ID', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'sku_name', description: 'SKU名称', type: 'string', nullable: true)]
    private ?string $skuName;

    #[OA\Property(property: 'warehouse_id', description: '仓库ID', type: 'integer')]
    private int $warehouseId;

    #[OA\Property(property: 'warehouse_name', description: '仓库名称', type: 'string', nullable: true)]
    private ?string $warehouseName;

    #[OA\Property(property: 'quantity', description: '总库存数量', type: 'integer')]
    private int $quantity;

    #[OA\Property(property: 'available_quantity', description: '可用库存数量', type: 'integer')]
    private int $availableQuantity;

    #[OA\Property(property: 'reserved_quantity', description: '预留库存数量', type: 'integer')]
    private int $reservedQuantity;

    #[OA\Property(property: 'alert_threshold', description: '库存预警阈值', type: 'integer', nullable: true)]
    private ?int $alertThreshold;

    #[OA\Property(property: 'last_updated', description: '最后更新时间', type: 'string', format: 'date-time')]
    private string $lastUpdated;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): void
    {
        $this->productName = $productName;
    }

    public function getSkuId(): int
    {
        return $this->skuId;
    }

    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
    }

    public function getSkuName(): ?string
    {
        return $this->skuName;
    }

    public function setSkuName(?string $skuName): void
    {
        $this->skuName = $skuName;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function setWarehouseId(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }

    public function getWarehouseName(): ?string
    {
        return $this->warehouseName;
    }

    public function setWarehouseName(?string $warehouseName): void
    {
        $this->warehouseName = $warehouseName;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getAvailableQuantity(): int
    {
        return $this->availableQuantity;
    }

    public function setAvailableQuantity(int $availableQuantity): void
    {
        $this->availableQuantity = $availableQuantity;
    }

    public function getReservedQuantity(): int
    {
        return $this->reservedQuantity;
    }

    public function setReservedQuantity(int $reservedQuantity): void
    {
        $this->reservedQuantity = $reservedQuantity;
    }

    public function getAlertThreshold(): ?int
    {
        return $this->alertThreshold;
    }

    public function setAlertThreshold(?int $alertThreshold): void
    {
        $this->alertThreshold = $alertThreshold;
    }

    public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(string $lastUpdated): void
    {
        $this->lastUpdated = $lastUpdated;
    }
}
