<?php

declare(strict_types=1);

namespace App\Modules\Web\Middleware;

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
            resource_path('lang/zh-CN/common.php'),
            resource_path('lang/zh-CN/flow.php'),
        ]);

        return $next($request);
    }
}
