<?php

declare(strict_types=1);

namespace App\Modules\Product\Enums\Category;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

/**
 * 商品分类模块枚举
 */
enum CategoryErrorEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 查询列表错误
     */
    case QUERY_ERROR = 10301;

    /**
     * 查询数据不存在
     */
    case NOT_FOUND = 10302;

    /**
     * 新增数据失败
     */
    case CREATE_FAIL = 10303;

    /**
     * 新增数据错误
     */
    case CREATE_ERROR = 10304;

    /**
     * 获取详情错误
     */
    case SHOW_ERROR = 10305;

    /**
     * 更新数据失败
     */
    case UPDATE_FAIL = 10306;

    /**
     * 更新数据错误
     */
    case UPDATE_ERROR = 10307;

    /**
     * 删除数据失败
     */
    case DESTROY_FAIL = 10308;

    /**
     * 删除数据错误
     */
    case DESTROY_ERROR = 10309;
}
