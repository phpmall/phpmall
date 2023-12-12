<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\Wechat\Services\WechatService;
use App\Foundation\Exceptions\CustomException;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OAuthService
{
    private UserService $userService;

    private WechatService $wechatService;

    private Application $wechat;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->wechatService = new WechatService();
        $this->wechat = $this->wechatService->officialAccount();
    }

    /**
     * 生成授权链接
     */
    public function redirect(Request $request): RedirectResponse
    {
        $config = config('wechat.official_account.oauth');

        $callbackUrl = url($config['callback'], $request->get())->domain(true);

        $redirectUrl = $this->wechat->oauth->scopes($config['scopes'])->redirect($callbackUrl);

        return redirect($redirectUrl);
    }

    /**
     * 授权回调
     *
     * @throws CustomException
     */
    public function callback(Request $request): JsonResponse
    {
        $code = $request->get('code');

        if (empty($code)) {
            throw new CustomException('Wechat authorization callback failed');
        }

        $user = $this->wechat->oauth->userFromCode($code);

        $userId = $this->userService->userInfoUpdateOrInsert($user);

        return $this->succeed($userId);
    }
}
