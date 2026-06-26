<?php

declare(strict_types=1);

namespace App\Api\Supplier\Responses\PurchaseOrder;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierPurchaseOrderResponse')]
class PurchaseOrderResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '采购订单ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'order_no', description: '采购订单编号', type: 'string')]
    private string $orderNo;

    #[OA\Property(property: 'buyer_id', description: '采购商ID', type: 'integer')]
    private int $buyerId;

    #[OA\Property(property: 'buyer_name', description: '采购商名称', type: 'string', nullable: true)]
    private ?string $buyerName;

    #[OA\Property(property: 'total_amount', description: '订单总金额(分)', type: 'integer')]
    private int $totalAmount;

    #[OA\Property(property: 'status', description: '订单状态:0待确认,1待发货,2已发货,3已完成,4已取消', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'logistics_company', description: '物流公司', type: 'string', nullable: true)]
    private ?string $logisticsCompany;

    #[OA\Property(property: 'logistics_no', description: '物流单号', type: 'string', nullable: true)]
    private ?string $logisticsNo;

    #[OA\Property(property: 'remark', description: '订单备注', type: 'string', nullable: true)]
    private ?string $remark;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'shipped_at', description: '发货时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $shippedAt;

    #[OA\Property(property: 'confirmed_at', description: '确认时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $confirmedAt;

    #[OA\Property(
        property: 'items',
        description: '订单商品列表',
        type: 'array',
        items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'product_id', type: 'integer'),
            new OA\Property(property: 'product_name', type: 'string'),
            new OA\Property(property: 'price', type: 'integer', description: '单价(分)'),
            new OA\Property(property: 'quantity', type: 'integer'),
            new OA\Property(property: 'total_price', type: 'integer', description: '小计(分)'),
        ]),
        nullable: true
    )]
    private ?array $items;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    public function getBuyerId(): int
    {
        return $this->buyerId;
    }

    public function setBuyerId(int $buyerId): void
    {
        $this->buyerId = $buyerId;
    }

    public function getBuyerName(): ?string
    {
        return $this->buyerName;
    }

    public function setBuyerName(?string $buyerName): void
    {
        $this->buyerName = $buyerName;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getLogisticsCompany(): ?string
    {
        return $this->logisticsCompany;
    }

    public function setLogisticsCompany(?string $logisticsCompany): void
    {
        $this->logisticsCompany = $logisticsCompany;
    }

    public function getLogisticsNo(): ?string
    {
        return $this->logisticsNo;
    }

    public function setLogisticsNo(?string $logisticsNo): void
    {
        $this->logisticsNo = $logisticsNo;
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

    public function getShippedAt(): ?string
    {
        return $this->shippedAt;
    }

    public function setShippedAt(?string $shippedAt): void
    {
        $this->shippedAt = $shippedAt;
    }

    public function getConfirmedAt(): ?string
    {
        return $this->confirmedAt;
    }

    public function setConfirmedAt(?string $confirmedAt): void
    {
        $this->confirmedAt = $confirmedAt;
    }

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(?array $items): void
    {
        $this->items = $items;
    }
}
