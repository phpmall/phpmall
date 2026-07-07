<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Order;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderPreviewResponse')]
class OrderPreviewResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'total_amount', description: '商品总金额(分)', type: 'integer')]
    private int $totalAmount;

    #[OA\Property(property: 'discount_amount', description: '优惠金额(分)', type: 'integer')]
    private int $discountAmount;

    #[OA\Property(property: 'freight_amount', description: '运费(分)', type: 'integer')]
    private int $freightAmount;

    #[OA\Property(property: 'pay_amount', description: '实付金额(分)', type: 'integer')]
    private int $payAmount;

    #[OA\Property(property: 'item_count', description: '商品总数量', type: 'integer')]
    private int $itemCount;

    #[OA\Property(
        property: 'merchant_groups',
        description: '按商家分组的订单预览',
        type: 'array',
        items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'merchant_id', type: 'integer', description: '商家ID'),
            new OA\Property(property: 'merchant_name', type: 'string', description: '商家名称'),
            new OA\Property(property: 'product_amount', type: 'integer', description: '商品金额(分)'),
            new OA\Property(property: 'freight_amount', type: 'integer', description: '运费(分)'),
            new OA\Property(property: 'items', type: 'array', items: new OA\Items(type: 'object', properties: [
                new OA\Property(property: 'sku_id', type: 'integer', description: 'SKU ID'),
                new OA\Property(property: 'product_name', type: 'string', description: '商品名称'),
                new OA\Property(property: 'sku_name', type: 'string', description: 'SKU规格'),
                new OA\Property(property: 'image', type: 'string', description: '商品图片'),
                new OA\Property(property: 'price', type: 'integer', description: '单价(分)'),
                new OA\Property(property: 'quantity', type: 'integer', description: '数量'),
                new OA\Property(property: 'total_price', type: 'integer', description: '小计(分)'),
            ])),
        ])
    )]
    private array $merchantGroups;

    #[OA\Property(
        property: 'address',
        description: '收货地址',
        type: 'object',
        properties: [
            new OA\Property(property: 'id', type: 'integer', description: '地址ID'),
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

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
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

    public function getMerchantGroups(): array
    {
        return $this->merchantGroups;
    }

    public function setMerchantGroups(array $merchantGroups): void
    {
        $this->merchantGroups = $merchantGroups;
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
