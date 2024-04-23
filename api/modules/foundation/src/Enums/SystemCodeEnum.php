<?php

declare(strict_types=1);

namespace Juling\Foundation\Enums;

use Juling\Foundation\Contracts\EnumMethodInterface;

/**
 * 全局枚举
 */
enum SystemCodeEnum: int implements EnumMethodInterface
{
    use EnumMethods;

    /**
     * 操作成功
     */
    case SUCCESS = 200;

    /**
     * 错误的请求
     */
    case BAD_REQUEST = 400;

    /**
     * 未授权
     */
    case UNAUTHORIZED = 401;

    /**
     * 禁止访问
     */
    case FORBIDDEN = 403;

    /**
     * 未找到
     */
    case NOT_FOUND = 404;

    /**
     * 方法不被允许
     */
    case METHOD_NOT_ALLOWED = 405;

    /**
     * 服务器内部错误
     */
    case INTERNAL_SERVER_ERROR = 500;

    /**
     * 未实现
     */
    case NOT_IMPLEMENTED = 501;

    /**
     * 错误的网关
     */
    case BAD_GATEWAY = 502;

    /**
     * 服务不可用
     */
    case SERVICE_UNAVAILABLE = 503;

    /**
     * 网关超时
     */
    case GATEWAY_TIMEOUT = 504;

    /**
     * 系统维护中
     */
    case SYSTEM_MAINTENANCE = 999;

    /**
     * 系统错误
     */
    case SYSTEM_ERROR = 1000;
}
