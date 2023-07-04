<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Privilege
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // dd($guards);

        return $next($request);
    }
}
