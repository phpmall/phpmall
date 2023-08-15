<?php

declare(strict_types=1);

namespace App\Gateways\Auth\Controllers;

use App\Constants\GlobalConst;
use App\Exceptions\CustomException;
use App\Gateways\Auth\Requests\Login\LoginMobileRequest;
use App\Gateways\Auth\Requests\Login\LoginSmsRequest;
use App\Gateways\Auth\Responses\LoginResponse;
use App\Gateways\Auth\Services\AuthService;
use App\Services\UserService;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Laractl\Captcha\Captcha;
use OpenApi\Attributes as OA;

class LoginController extends BaseController
{
    public function showLoginForm(): Renderable
    {
        return $this->display('auth::login');
    }

    #[OA\Post(path: '/login', summary: '通过手机号和密码登录', tags: ['登录'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginMobileRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function login(LoginMobileRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $captchaService = new Captcha();
            if (! $captchaService->check($data['uuid'], $data['captcha'])) {
                throw new CustomException('图片验证码输入错误');
            }

            $credentials = [
                'mobile' => $data['mobile'],
                'password' => $data['password'],
            ];
            $remember = $data['remember'] ?? 'off';

            if (Auth::attempt($credentials, $remember === 'on')) {
                $authService = new AuthService();
                $token = $authService->createToken([
                    GlobalConst::JWT_USER_ID => Auth::id(),
                ]);

                // dd(Auth::user()->createToken('aa')->plainTextToken);
                // 6|8Wm5EBHMW99gmOOCEuJPmanqOvtK94YXGfDw5yhT

                $response = new LoginResponse();
                $response->setToken($token);

                return $this->success($response->toArray());
            }

            return $this->error('手机号码登录失败');
        } catch (CustomException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $this->error('帐号登录错误');
        }
    }

    #[OA\Post(path: '/login/mobile', summary: '通过手机短信验证码登录', tags: ['登录'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginSmsRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function mobile(LoginMobileRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // 校验短信验证码
            $smsCode = Cache::get(GlobalConst::SMS_CACHE_PREFIX.$data['mobile']);
            if ($smsCode !== $data['code']) {
                return $this->error('短信验证码不正确');
            }

            $userService = new UserService();
            $userOutput = $userService->findOneByMobile($data['mobile']);

            $authService = new AuthService();
            $token = $authService->createToken([
                GlobalConst::JWT_USER_ID => $userOutput->getId(),
            ]);

            $response = new LoginResponse();
            $response->setToken($token);

            return $this->success($response->toArray());
        } catch (CustomException $e) {
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $this->error('短信登录错误');
        }
    }
}
