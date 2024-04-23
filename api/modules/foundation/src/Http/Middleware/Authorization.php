<?php

declare(strict_types=1);

namespace Juling\Foundation\Http\Middleware;

use Closure;

class Authorization
{
    public function handle($request, Closure $next, ...$guards)
    {
        dump($guards);

        return $next($request);
    }
}
