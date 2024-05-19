<?php

declare(strict_types=1);

namespace App\Modules\Product\Enums\Product;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

/**
 * 商品模块枚举
 */
enum ProductErrorEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 查询列表错误
     */
    case QUERY_ERROR = 10601;

    /**
     * 查询数据不存在
     */
    case NOT_FOUND = 10602;

    /**
     * 新增数据失败
     */
    case CREATE_FAIL = 10603;

    /**
     * 新增数据错误
     */
    case CREATE_ERROR = 10604;

    /**
     * 获取详情错误
     */
    case SHOW_ERROR = 10605;

    /**
     * 更新数据失败
     */
    case UPDATE_FAIL = 10606;

    /**
     * 更新数据错误
     */
    case UPDATE_ERROR = 10607;

    /**
     * 删除数据失败
     */
    case DESTROY_FAIL = 10608;

    /**
     * 删除数据错误
     */
    case DESTROY_ERROR = 10609;
}
