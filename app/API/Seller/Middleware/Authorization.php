<?php

declare(strict_types=1);

namespace App\API\Seller\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authorization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards): mixed
    {
        return $next($request);
    }
}
