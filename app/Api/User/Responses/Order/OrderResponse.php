<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Order;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderResponse')]
class OrderResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '订单ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'order_no', description: '订单编号', type: 'string')]
    private string $orderNo;

    #[OA\Property(property: 'status', description: '订单状态:0待付款,1待发货,2待收货,3已完成,4已取消', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'total_amount', description: '订单总金额(分)', type: 'integer')]
    private int $totalAmount;

    #[OA\Property(property: 'pay_amount', description: '实付金额(分)', type: 'integer')]
    private int $payAmount;

    #[OA\Property(property: 'discount_amount', description: '优惠金额(分)', type: 'integer', nullable: true)]
    private ?int $discountAmount;

    #[OA\Property(property: 'freight_amount', description: '运费(分)', type: 'integer', nullable: true)]
    private ?int $freightAmount;

    #[OA\Property(property: 'item_count', description: '商品数量', type: 'integer')]
    private int $itemCount;

    #[OA\Property(property: 'remark', description: '订单备注', type: 'string', nullable: true)]
    private ?string $remark;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'paid_at', description: '支付时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $paidAt;

    #[OA\Property(property: 'shipped_at', description: '发货时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $shippedAt;

    #[OA\Property(property: 'confirmed_at', description: '确认收货时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $confirmedAt;

    #[OA\Property(
        property: 'items',
        description: '订单商品列表',
        type: 'array',
        items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'id', type: 'integer', description: '商品项ID'),
            new OA\Property(property: 'product_name', type: 'string', description: '商品名称'),
            new OA\Property(property: 'sku_name', type: 'string', description: 'SKU规格名称'),
            new OA\Property(property: 'image', type: 'string', description: '商品图片'),
            new OA\Property(property: 'price', type: 'integer', description: '单价(分)'),
            new OA\Property(property: 'quantity', type: 'integer', description: '购买数量'),
            new OA\Property(property: 'total_price', type: 'integer', description: '小计(分)'),
        ])
    )]
    private array $items;

    #[OA\Property(
        property: 'address',
        description: '收货地址',
        type: 'object',
        properties: [
            new OA\Property(property: 'contact_name', type: 'string', description: '联系人姓名'),
            new OA\Property(property: 'contact_phone', type: 'string', description: '联系人手机号'),
            new OA\Property(property: 'province', type: 'string', description: '省份'),
            new OA\Property(property: 'city', type: 'string', description: '城市'),
            new OA\Property(property: 'district', type: 'string', description: '区县'),
            new OA\Property(property: 'detail', type: 'string', description: '详细地址'),
        ],
        nullable: true
    )]
    private ?array $address;

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

    public function getDiscountAmount(): ?int
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?int $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getFreightAmount(): ?int
    {
        return $this->freightAmount;
    }

    public function setFreightAmount(?int $freightAmount): void
    {
        $this->freightAmount = $freightAmount;
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

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getAddress(): ?array
    {
        return $this->address;
    }

    public function setAddress(?array $address): void
    {
        $this->address = $address;
    }
}
