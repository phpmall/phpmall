<?php

declare(strict_types=1);

namespace App\Domains\Order\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Support\EnumMethods;

/**
 * 订单状态枚举
 */
enum OrderOrderTypeStatusEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 待付款
     */
    case Status10 = 10;

    /**
     * 已支付
     */
    case Status20 = 20;

    /**
     * 待发货
     */
    case Status30 = 30;

    /**
     * 已发货
     */
    case Status40 = 40;

    /**
     * 待收货
     */
    case Status50 = 50;

    /**
     * 已收货
     */
    case Status60 = 60;

    /**
     * 已完成
     */
    case Status70 = 70;

    /**
     * 已取消
     */
    case Status80 = 80;

    /**
     * 退款中
     */
    case Status90 = 90;

    /**
     * 已退款
     */
    case Status100 = 100;
}
