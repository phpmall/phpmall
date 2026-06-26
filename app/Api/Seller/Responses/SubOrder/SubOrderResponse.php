<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\SubOrder;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerSubOrderResponse')]
class SubOrderResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '子订单ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'sub_order_no', description: '子订单编号', type: 'string')]
    private string $subOrderNo;

    #[OA\Property(property: 'order_id', description: '父订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'status', description: '子订单状态:0待付款,1待发货,2待收货,3已完成,4已取消', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'total_amount', description: '子订单总金额(分)', type: 'integer')]
    private int $totalAmount;

    #[OA\Property(property: 'pay_amount', description: '实付金额(分)', type: 'integer')]
    private int $payAmount;

    #[OA\Property(property: 'item_count', description: '商品数量', type: 'integer')]
    private int $itemCount;

    #[OA\Property(property: 'remark', description: '备注', type: 'string', nullable: true)]
    private ?string $remark;

    #[OA\Property(property: 'items', description: '子订单商品项', type: 'array', items: new OA\Items(type: 'object', properties: [
        new OA\Property(property: 'id', description: '商品项ID', type: 'integer'),
        new OA\Property(property: 'product_id', description: '商品ID', type: 'integer'),
        new OA\Property(property: 'product_name', description: '商品名称', type: 'string'),
        new OA\Property(property: 'product_image', description: '商品图片', type: 'string', nullable: true),
        new OA\Property(property: 'sku_id', description: 'SKU ID', type: 'integer'),
        new OA\Property(property: 'sku_spec', description: 'SKU规格', type: 'string', nullable: true),
        new OA\Property(property: 'price', description: '单价(分)', type: 'integer'),
        new OA\Property(property: 'quantity', description: '数量', type: 'integer'),
        new OA\Property(property: 'total_amount', description: '小计金额(分)', type: 'integer'),
    ]))]
    private array $items;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'paid_at', description: '支付时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $paidAt;

    #[OA\Property(property: 'shipped_at', description: '发货时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $shippedAt;

    #[OA\Property(property: 'confirmed_at', description: '确认收货时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $confirmedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSubOrderNo(): string
    {
        return $this->subOrderNo;
    }

    public function setSubOrderNo(string $subOrderNo): void
    {
        $this->subOrderNo = $subOrderNo;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getPayAmount(): int
    {
        return $this->payAmount;
    }

    public function setPayAmount(int $payAmount): void
    {
        $this->payAmount = $payAmount;
    }

    public function getItemCount(): int
    {
        return $this->itemCount;
    }

    public function setItemCount(int $itemCount): void
    {
        $this->itemCount = $itemCount;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getPaidAt(): ?string
    {
        return $this->paidAt;
    }

    public function setPaidAt(?string $paidAt): void
    {
        $this->paidAt = $paidAt;
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
}
