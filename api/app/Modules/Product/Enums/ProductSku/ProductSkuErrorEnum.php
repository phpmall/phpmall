<?php

declare(strict_types=1);

namespace App\Modules\Product\Enums\ProductSku;

use Juling\Foundation\Contracts\EnumMethodInterface;
use Juling\Foundation\Enums\EnumMethods;

/**
 * 商品货品模块枚举
 */
enum ProductSkuErrorEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 查询列表错误
     */
    case QUERY_ERROR = 10501;

    /**
     * 查询数据不存在
     */
    case NOT_FOUND = 10502;

    /**
     * 新增数据失败
     */
    case CREATE_FAIL = 10503;

    /**
     * 新增数据错误
     */
    case CREATE_ERROR = 10504;

    /**
     * 获取详情错误
     */
    case SHOW_ERROR = 10505;

    /**
     * 更新数据失败
     */
    case UPDATE_FAIL = 10506;

    /**
     * 更新数据错误
     */
    case UPDATE_ERROR = 10507;

    /**
     * 删除数据失败
     */
    case DESTROY_FAIL = 10508;

    /**
     * 删除数据错误
     */
    case DESTROY_ERROR = 10509;
}
