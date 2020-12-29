<?php

declare(strict_types=1);

namespace app\support;

use think\facade\Route;

/**
 * Class Request
 * @package app\support
 */
class Request
{
    /**
     * @var array
     */
    private static $aliasMap = [
        // 'console' => ADMIN_PATH,
    ];

    /**
     * Router
     */
    public static function router()
    {
        $m = glob(app_path('controller/*'), GLOB_ONLYDIR);

        foreach ($m as $v) {
            $k = basename($v);
            $v = isset(self::$aliasMap[$k]) ? self::$aliasMap[$k] : $k;
            Route::group($v, function () {
                self::routeRule();
            })->prefix($k . '.');
        }

        self::routeRule();
    }

    /**
     * Route rules
     */
    public static function routeRule()
    {
        Route::get(':c/:a', ':c/:a');
        Route::post(':c/:a', ':c/:aHandler');
        Route::get(':c', ':c/index');
        Route::get('/', 'Index/index');
    }
}
