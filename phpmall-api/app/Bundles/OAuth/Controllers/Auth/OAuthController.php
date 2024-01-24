<?php

declare(strict_types=1);

namespace App\Bundles\OAuth\Controllers\Auth;

use App\Api\Auth\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Overtrue\LaravelSocialite\Socialite;
use Throwable;

class OAuthController extends BaseController
{
    #[OA\Post(path: '/oauth/redirect', summary: '获取授权跳转地址', tags: ['开放授权登录'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function redirect(Request $request): RedirectResponse
    {
        try {
            $type = $request->get('type', '');
            $path = Socialite::create($type)->redirect();

            return redirect()->to($path);
        } catch (Throwable $e) {
            abort(501, $e->getMessage());
        }
    }

    #[OA\Post(path: '/oauth/callback', summary: '授权登录回调地址', tags: ['开放授权登录'])]
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

    #[OA\Post(path: '/oauth/bind', summary: '新用户绑定接口', tags: ['开放授权登录'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function bind()
    {

    }
}
