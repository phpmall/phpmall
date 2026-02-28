<?php

declare(strict_types=1);

namespace App\Modules\User\Middleware;

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
            $request->offsetSet('act', 'default');
        }

        lang([
            resource_path('lang/zh-CN/common.php'),
            resource_path('lang/zh-CN/user.php'),
        ]);

        return $next($request);
    }
}
