<?php

declare(strict_types=1);

namespace App\Api\Auth\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Overtrue\LaravelSocialite\Socialite;
use Throwable;

class OAuthController extends BaseController
{
    #[OA\Post(path: '/api/oauth/redirect', summary: '获取授权跳转地址', tags: ['授权登录'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function redirect(Request $request): RedirectResponse
    {
        try {
            $type = $request->get('type');

            return redirect()->to(Socialite::create($type)->redirect());
        } catch (Throwable $e) {
            abort(501, $e->getMessage());
        }
    }

    #[OA\Post(path: '/api/oauth/callback', summary: '授权登录回调地址', tags: ['授权登录'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function callback(Request $request): RedirectResponse
    {
        try {
            $type = $request->get('type');
            $code = $request->query('code');

            $user = Socialite::create($type)->userFromCode($code);

            return redirect()->to('/');
        } catch (Throwable $e) {
            abort(501, $e->getMessage());
        }
    }

    #[OA\Post(path: '/api/oauth/bind', summary: '新用户绑定接口', tags: ['授权登录'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function bind()
    {

    }
}
