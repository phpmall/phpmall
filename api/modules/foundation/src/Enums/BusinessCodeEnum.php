<?php

declare(strict_types=1);

namespace Juling\Foundation\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;

/**
 * 业务错误枚举
 */
enum BusinessCodeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 查询失败
     */
    case QUERY_ERROR = 10001;

    /**
     * 新增失败
     */
    case CREATE_ERROR = 10002;

    /**
     * 更新失败
     */
    case UPDATE_ERROR = 10003;

    /**
     * 删除失败
     */
    case DESTROY_ERROR = 10004;

    /**
     * 数据未找到
     */
    case DATA_NOT_FOUND = 10101;

    /**
     * 数据已存在
     */
    case DATA_EXIST = 10102;

    /**
     * 数据访问错误
     */
    case DATA_ACCESS_ERROR = 10103;

    /**
     * 业务错误
     */
    case BUSINESS_ERROR = 10104;

    /**
     * 权限拒绝
     */
    case PERMISSION_DENIED = 10201;
}
