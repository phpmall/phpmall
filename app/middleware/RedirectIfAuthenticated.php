<?php

declare (strict_types=1);

namespace app\middleware;

use Closure;
use think\Request;
use think\Response;

/**
 * Class RedirectIfAuthenticated
 * @package app\middleware
 */
class RedirectIfAuthenticated
{
    /**
     * 处理请求
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        //
    }
}
