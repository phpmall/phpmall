<?php

declare (strict_types=1);

namespace app\middleware;

use Closure;
use think\Request;
use think\Response;

/**
 * Class Authenticate
 * @package app\middleware
 */
class Authenticate
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
        /**
        // 认证
        if (session('?auth') === false) {
            $currentUrl = urlencode($request->url());
            return redirect('/passport/login?callback=' . $currentUrl);
        }

        // 资源
        $path = array_pad(explode('/', $request->pathinfo()), 3, 'index');
        $rule = implode('/', array_slice($path, 0, 3));

        // 授权
        $auth = new Auth();
        if (!$auth->check($rule, session('auth.id'))) {
            die('unable to access');
        }
        */
        $guard = explode('/', $request->pathinfo())[0];

        $jwt = $request->header('x-auth-token');

        return json($jwt[$guard]);
    }
}
