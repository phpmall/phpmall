<?php

declare(strict_types=1);

namespace App\Modules\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (empty($request->get('act'))) {
            $request->offsetSet('act', 'list');
        }

        lang([
            dirname(__DIR__).'/Languages/zh-CN/common.php',
            dirname(__DIR__).'/Languages/zh-CN/log_action.php',
            dirname(__DIR__).'/Languages/zh-CN/'.strtolower(basename($request->path())),
        ]);

        return $next($request);
    }
}
