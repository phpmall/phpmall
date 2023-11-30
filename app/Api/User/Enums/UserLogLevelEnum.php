<?php

declare(strict_types=1);

namespace App\Api\User\Enums;

/**
 * 日志等级
 */
enum UserLogLevelEnum: string
{
    /**
     * Debug
     */
    case Debug = 'debug';

    /**
     * Info
     */
    case Info = 'info';

    /**
     * Warning
     */
    case Warning = 'warning';

    /**
     * Error
     */
    case Error = 'error';
}
