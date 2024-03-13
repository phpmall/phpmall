<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\CodeEnumInterface;

/**
 * 全局异常枚举
 */
enum ErrorCodeEnum: int implements CodeEnumInterface
{
    use EnumMethods;

    // 成功：通常表示操作已成功执行

    /**
     * 操作成功
     */
    case SUCCESS = 200;

    // 客户端错误：这类错误通常由用户提供的信息引起的，如输入数据格式错误或请求的资源不存在。

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

    // 服务器端错误：当服务器无法处理请求时发生的错误。

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

    // 数据错误：与数据相关的错误，如数据访问异常、数据不存在或数据冲突。

    /**
     * 数据未找到
     */
    case DATA_NOT_FOUND = 1001;

    /**
     * 数据已存在
     */
    case DATA_EXIST = 1002;

    /**
     * 数据访问错误
     */
    case DATA_ACCESS_ERROR = 1003;

    // 业务错误：业务逻辑处理中出现的错误，通常根据具体业务场景定义。

    /**
     * 业务错误
     */
    case BUSINESS_ERROR = 2001;

    // 验证错误：请求数据验证失败时使用。

    /**
     * 验证失败
     */
    case VALIDATION_ERROR = 3001;

    // 权限错误：用户尝试执行他们没有权限执行的操作时使用。

    /**
     * 权限拒绝
     */
    case PERMISSION_DENIED = 4001;

    // 系统错误：因系统维护或遇到不可预知的错误时使用

    /**
     * 系统维护中
     */
    case SYSTEM_MAINTENANCE = 5001;

    /**
     * 系统错误
     */
    case SYSTEM_ERROR = 5002;
}
