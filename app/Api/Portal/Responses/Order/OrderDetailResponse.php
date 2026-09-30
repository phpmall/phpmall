<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Order;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalOrderDetailResponse',
    title: '订单详情出参',
    description: '包含订单主体状态、金额、明细快照及物流包裹信息'
)]
class OrderDetailResponse
{
    #[OA\Property(property: 'id', description: '订单ID', type: 'integer', example: 10086)]
    public int $id;

    #[OA\Property(property: 'order_no', description: '订单编号', type: 'string', example: 'O20260930152901abcd1234')]
    public string $order_no;

    #[OA\Property(property: 'status', description: '订单状态：10-待付款，20-已支付，30-待发货，40-已发货，50-待收货，60-已收货，70-已完成，80-已取消，90-退款中，100-已退款', type: 'integer', example: 10)]
    public int $status;

    #[OA\Property(property: 'pay_status', description: '支付状态：0-未支付，20-已支付，30-部分退款，100-全额退款', type: 'integer', example: 0)]
    public int $pay_status;

    #[OA\Property(property: 'product_amount', description: '商品总额（分）', type: 'integer', example: 499900)]
    public int $product_amount;

    #[OA\Property(property: 'freight_amount', description: '运费（分）', type: 'integer', example: 0)]
    public int $freight_amount;

    #[OA\Property(property: 'discount_amount', description: '优惠扣减（分）', type: 'integer', example: 0)]
    public int $discount_amount;

    #[OA\Property(property: 'pay_amount', description: '实付金额（分）', type: 'integer', example: 499900)]
    public int $pay_amount;

    #[OA\Property(property: 'remark', description: '买家备注', type: 'string', nullable: true, example: '请尽快发货')]
    public ?string $remark = null;

    #[OA\Property(property: 'created_at', description: '下单时间', type: 'string', example: '2026-09-30 15:30:00')]
    public string $created_at;

    #[OA\Property(
        property: 'items',
        description: '订单商品列表',
        type: 'array',
        items: new OA\Items(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'product_id', type: 'integer', example: 1001),
                new OA\Property(property: 'sku_id', type: 'integer', example: 2001),
                new OA\Property(property: 'product_title', type: 'string', example: '旗舰智能手机'),
                new OA\Property(property: 'product_image', type: 'string', example: '/images/phone.jpg'),
                new OA\Property(property: 'sku_specs', type: 'object', example: ['颜色' => '曜石黑']),
                new OA\Property(property: 'price', type: 'integer', example: 499900),
                new OA\Property(property: 'quantity', type: 'integer', example: 1),
                new OA\Property(property: 'total_amount', type: 'integer', example: 499900),
            ]
        )
    )]
    public array $items;

    #[OA\Property(
        property: 'shipments',
        description: '物流包裹列表',
        type: 'array',
        items: new OA\Items(
            properties: [
                new OA\Property(property: 'logistics_company', type: 'string', example: '顺丰速运'),
                new OA\Property(property: 'tracking_no', type: 'string', example: 'SF1234567890'),
            ]
        )
    )]
    public array $shipments;
}
