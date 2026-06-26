<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Order;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerOrderResponse')]
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

    #[OA\Property(property: 'discount_amount', description: '优惠金额(分)', type: 'integer')]
    private int $discountAmount;

    #[OA\Property(property: 'freight_amount', description: '运费金额(分)', type: 'integer')]
    private int $freightAmount;

    #[OA\Property(property: 'item_count', description: '商品数量', type: 'integer')]
    private int $itemCount;

    #[OA\Property(property: 'remark', description: '订单备注', type: 'string', nullable: true)]
    private ?string $remark;

    #[OA\Property(property: 'buyer_info', description: '买家信息', type: 'object', properties: [
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'nickname', description: '用户昵称', type: 'string', nullable: true),
        new OA\Property(property: 'avatar', description: '用户头像', type: 'string', nullable: true),
        new OA\Property(property: 'phone', description: '用户手机号', type: 'string', nullable: true),
    ])]
    private array $buyerInfo;

    #[OA\Property(property: 'address', description: '收货地址', type: 'object', properties: [
        new OA\Property(property: 'consignee', description: '收货人姓名', type: 'string'),
        new OA\Property(property: 'phone', description: '收货人手机号', type: 'string'),
        new OA\Property(property: 'province', description: '省份', type: 'string'),
        new OA\Property(property: 'city', description: '城市', type: 'string'),
        new OA\Property(property: 'district', description: '区县', type: 'string'),
        new OA\Property(property: 'address', description: '详细地址', type: 'string'),
    ])]
    private array $address;

    #[OA\Property(property: 'items', description: '订单商品项', type: 'array', items: new OA\Items(type: 'object', properties: [
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

    public function getDiscountAmount(): int
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(int $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getFreightAmount(): int
    {
        return $this->freightAmount;
    }

    public function setFreightAmount(int $freightAmount): void
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

    public function getBuyerInfo(): array
    {
        return $this->buyerInfo;
    }

    public function setBuyerInfo(array $buyerInfo): void
    {
        $this->buyerInfo = $buyerInfo;
    }

    public function getAddress(): array
    {
        return $this->address;
    }

    public function setAddress(array $address): void
    {
        $this->address = $address;
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
