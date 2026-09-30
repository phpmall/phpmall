<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Cart;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalCartListResponse',
    title: '购物车列表与汇总结算数据',
    description: '包含购物车内所有条目及勾选商品总金额'
)]
class CartListResponse
{
    #[OA\Property(
        property: 'items',
        description: '购物车明细列表',
        type: 'array',
        items: new OA\Items(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 1),
                new OA\Property(property: 'product_id', type: 'integer', example: 1001),
                new OA\Property(property: 'sku_id', type: 'integer', example: 2001),
                new OA\Property(property: 'quantity', type: 'integer', example: 2),
                new OA\Property(property: 'is_selected', type: 'boolean', example: true),
                new OA\Property(property: 'is_valid', type: 'boolean', example: true),
                new OA\Property(property: 'product_title', type: 'string', example: '旗舰智能手机'),
                new OA\Property(property: 'product_image', type: 'string', example: '/images/phone.jpg'),
                new OA\Property(property: 'sku_specs', type: 'object', example: ['颜色' => '曜石黑']),
                new OA\Property(property: 'price', type: 'integer', example: 499900),
                new OA\Property(property: 'stock', type: 'integer', example: 100),
                new OA\Property(property: 'subtotal', type: 'integer', example: 999800),
            ]
        )
    )]
    public array $items;

    #[OA\Property(property: 'total_quantity', description: '购物车总件数', type: 'integer', example: 3)]
    public int $total_quantity;

    #[OA\Property(property: 'selected_quantity', description: '已勾选件数', type: 'integer', example: 2)]
    public int $selected_quantity;

    #[OA\Property(property: 'total_amount', description: '购物车总金额（分）', type: 'integer', example: 1499700)]
    public int $total_amount;

    #[OA\Property(property: 'selected_amount', description: '已勾选商品总金额（分）', type: 'integer', example: 999800)]
    public int $selected_amount;
}
