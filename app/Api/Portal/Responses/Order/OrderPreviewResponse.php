<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Order;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalOrderPreviewResponse',
    title: '订单预览验价出参',
    description: '结算商品明细、商品小计、运费与最终实付金额'
)]
class OrderPreviewResponse
{
    #[OA\Property(
        property: 'items',
        description: '商品明细',
        type: 'array',
        items: new OA\Items(
            properties: [
                new OA\Property(property: 'sku_id', type: 'integer', example: 2001),
                new OA\Property(property: 'product_id', type: 'integer', example: 1001),
                new OA\Property(property: 'product_title', type: 'string', example: '旗舰智能手机'),
                new OA\Property(property: 'product_image', type: 'string', example: '/images/phone.jpg'),
                new OA\Property(property: 'sku_specs', type: 'object', example: ['颜色' => '曜石黑']),
                new OA\Property(property: 'price', type: 'integer', example: 499900),
                new OA\Property(property: 'quantity', type: 'integer', example: 1),
                new OA\Property(property: 'subtotal', type: 'integer', example: 499900),
            ]
        )
    )]
    public array $items;

    #[OA\Property(property: 'product_amount', description: '商品总金额（分）', type: 'integer', example: 499900)]
    public int $product_amount;

    #[OA\Property(property: 'freight_amount', description: '运费金额（分）', type: 'integer', example: 0)]
    public int $freight_amount;

    #[OA\Property(property: 'discount_amount', description: '优惠扣减金额（分）', type: 'integer', example: 0)]
    public int $discount_amount;

    #[OA\Property(property: 'pay_amount', description: '最终实付金额（分）', type: 'integer', example: 499900)]
    public int $pay_amount;
}
