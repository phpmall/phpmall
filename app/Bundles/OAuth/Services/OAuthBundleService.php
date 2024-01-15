<?php

declare(strict_types=1);

namespace App\Bundles\OAuth\Services;

use App\Bundles\User\Services\UserBundleService;
use App\Bundles\Wechat\Services\OfficialAccountService;
use App\Foundation\Exceptions\CustomException;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OAuthBundleService
{
    private UserBundleService $userService;

    private OfficialAccountService $wechatService;

    private Application $wechat;

    public function __construct()
    {
        $this->userService = new UserBundleService();
        $this->wechatService = new OfficialAccountService();
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
