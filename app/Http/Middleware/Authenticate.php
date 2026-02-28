<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // 获取当前路由的 guard 类型
        $guard = $this->getGuardFromRequest($request);

        // 根据 guard 类型决定跳转地址
        return match ($guard) {
            'admin' => url('admin/login'),
            default => url('login'),
        };
    }

    /**
     * 从请求中获取 guard 类型
     */
    protected function getGuardFromRequest(Request $request): ?string
    {
        $route = $request->route();

        if (! $route) {
            return null;
        }

        // 获取路由中间件
        $middleware = $route->gatherMiddleware();

        foreach ($middleware as $m) {
            // 检查是否是 auth 中间件
            if (is_string($m) && str_starts_with($m, 'auth')) {
                // 解析 guard 参数,例如 "auth:admin" -> "admin"
                if (str_contains($m, ':')) {
                    $parts = explode(':', $m, 2);
                    $guards = explode(',', $parts[1]);

                    return $guards[0] ?? null;
                }

                // 如果只有 "auth" 没有参数,返回默认 guard
                return config('auth.defaults.guard', 'web');
            }
        }

        // 如果没有找到 auth 中间件,从请求路径判断
        $path = $request->path();
        if (str_starts_with($path, 'admin/') || str_starts_with($path, 'admin')) {
            return 'admin';
        }

        return config('auth.defaults.guard', 'web');
    }
}
