<?php

declare(strict_types=1);

namespace App\Api\Seller\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuth
{
    public function handle(Request $request, Closure $next)
    {
        // TODO 检查权限，附加商户信息，用于条件查询。

        return $next($request);
    }
}
