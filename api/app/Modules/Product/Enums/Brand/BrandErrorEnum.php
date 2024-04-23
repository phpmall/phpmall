<?php

declare(strict_types=1);

namespace App\Modules\Product\Enums\Brand;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

/**
 * 商品品牌模块枚举
 */
enum BrandErrorEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 查询列表错误
     */
    case QUERY_ERROR = 10201;

    /**
     * 查询数据不存在
     */
    case NOT_FOUND = 10202;

    /**
     * 新增数据失败
     */
    case CREATE_FAIL = 10203;

    /**
     * 新增数据错误
     */
    case CREATE_ERROR = 10204;

    /**
     * 获取详情错误
     */
    case SHOW_ERROR = 10205;

    /**
     * 更新数据失败
     */
    case UPDATE_FAIL = 10206;

    /**
     * 更新数据错误
     */
    case UPDATE_ERROR = 10207;

    /**
     * 删除数据失败
     */
    case DESTROY_FAIL = 10208;

    /**
     * 删除数据错误
     */
    case DESTROY_ERROR = 10209;
}
