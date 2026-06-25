<?php

declare(strict_types=1);

namespace App\Api\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuth
{
    public function handle(Request $request, Closure $next)
    {
        // TODO 检查权限

        return $next($request);
    }
}
