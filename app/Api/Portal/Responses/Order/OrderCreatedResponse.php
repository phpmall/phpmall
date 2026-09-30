<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Order;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalOrderCreatedResponse',
    title: '订单创建成功出参',
    description: '返回订单唯一业务编号及实付金额，用于跳转收银台或发起支付'
)]
class OrderCreatedResponse
{
    #[OA\Property(property: 'order_id', description: '订单主键ID', type: 'integer', example: 10086)]
    public int $order_id;

    #[OA\Property(property: 'order_no', description: '订单全局编号', type: 'string', example: 'O20260930152901abcd1234')]
    public string $order_no;

    #[OA\Property(property: 'pay_amount', description: '实付金额（分）', type: 'integer', example: 499900)]
    public int $pay_amount;

    #[OA\Property(property: 'status', description: '订单状态：10-待付款', type: 'integer', example: 10)]
    public int $status;
}
