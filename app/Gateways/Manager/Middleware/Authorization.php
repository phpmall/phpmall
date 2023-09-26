<?php

declare(strict_types=1);

namespace App\Gateways\Manager\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {

    }
}
