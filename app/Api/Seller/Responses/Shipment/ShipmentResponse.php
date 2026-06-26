<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Shipment;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerShipmentResponse')]
class ShipmentResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '发货单ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shipment_no', description: '发货单号', type: 'string')]
    private string $shipmentNo;

    #[OA\Property(property: 'order_id', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'logistics_company', description: '物流公司', type: 'string')]
    private string $logisticsCompany;

    #[OA\Property(property: 'tracking_no', description: '物流单号', type: 'string')]
    private string $trackingNo;

    #[OA\Property(property: 'status', description: '发货状态:0待发货,1已发货,2已签收', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'shipped_at', description: '发货时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $shippedAt;

    #[OA\Property(property: 'delivered_at', description: '送达时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $deliveredAt;

    #[OA\Property(property: 'items', description: '发货商品项', type: 'array', items: new OA\Items(type: 'object', properties: [
        new OA\Property(property: 'id', description: '商品项ID', type: 'integer'),
        new OA\Property(property: 'product_id', description: '商品ID', type: 'integer'),
        new OA\Property(property: 'product_name', description: '商品名称', type: 'string'),
        new OA\Property(property: 'sku_id', description: 'SKU ID', type: 'integer'),
        new OA\Property(property: 'sku_spec', description: 'SKU规格', type: 'string', nullable: true),
        new OA\Property(property: 'quantity', description: '发货数量', type: 'integer'),
    ]))]
    private array $items;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getShipmentNo(): string
    {
        return $this->shipmentNo;
    }

    public function setShipmentNo(string $shipmentNo): void
    {
        $this->shipmentNo = $shipmentNo;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getLogisticsCompany(): string
    {
        return $this->logisticsCompany;
    }

    public function setLogisticsCompany(string $logisticsCompany): void
    {
        $this->logisticsCompany = $logisticsCompany;
    }

    public function getTrackingNo(): string
    {
        return $this->trackingNo;
    }

    public function setTrackingNo(string $trackingNo): void
    {
        $this->trackingNo = $trackingNo;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getShippedAt(): ?string
    {
        return $this->shippedAt;
    }

    public function setShippedAt(?string $shippedAt): void
    {
        $this->shippedAt = $shippedAt;
    }

    public function getDeliveredAt(): ?string
    {
        return $this->deliveredAt;
    }

    public function setDeliveredAt(?string $deliveredAt): void
    {
        $this->deliveredAt = $deliveredAt;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
