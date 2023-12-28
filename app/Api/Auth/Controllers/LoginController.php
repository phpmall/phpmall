<?php

declare(strict_types=1);

namespace App\Api\Auth\Controllers;

use App\Api\Auth\Requests\Login\LoginRequest;
use App\Api\Auth\Requests\Login\LoginSmsRequest;
use App\Api\Auth\Responses\LoginResponse;
use App\Api\Auth\Services\AuthService;
use App\Api\Auth\Services\Input\LoginViaMobileInput;
use App\Api\Auth\Services\LoginService;
use App\Bundles\User\Enums\UserStatusEnum;
use App\Bundles\User\Services\UserService;
use App\Foundation\Constants\Constant;
use App\Foundation\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Juling\Captcha\Captcha;
use OpenApi\Attributes as OA;
use Throwable;

class LoginController extends BaseController
{
    #[OA\Post(path: '/login/mobile', summary: '通过手机号和密码登录', tags: ['认证管理'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function mobile(LoginRequest $request): JsonResponse
    {
        try {
            $formData = $request->validated();

            $captchaService = new Captcha();
            if (! $captchaService->check($request->post('uuid'), $request->post('captcha'))) {
                throw new CustomException('图片验证码输入错误');
            }

            $credentials = [
                'mobile' => $request->post('mobile'),
                'password' => $request->post('password'),
            ];
            $remember = $data['remember'] ?? 'off';

            if (Auth::attempt($credentials, $remember === 'on')) {
                $authService = new AuthService();
                $token = $authService->createToken([
                    Constant::JWT_USER_ID => Auth::id(),
                ]);

                // dd(Auth::user()->createToken('aa')->plainTextToken);
                // 6|8Wm5EBHMW99gmOOCEuJPmanqOvtK94YXGfDw5yhT

                $response = new LoginResponse();
                $response->setToken($token);

                return $this->success($response->toArray());
            }

            $loginInput = new LoginViaMobileInput();
            $loginInput->setMobile($request->post('mobile'));
            $loginInput->setPassword($request->post('password'));
            $loginInput->setCaptcha($request->post('captcha'));
            $loginInput->setUuid($request->post('uuid'));

            $loginService = new LoginService();
            $adminUser = $loginService->mobile($loginInput);
            $token = $adminUser->createToken('token')->plainTextToken;

            $loginResponse = new LoginResponse();
            $loginResponse->setToken($token);

            return $this->success($loginResponse->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e->getMessage());

            return $this->error($e->getMessage());
        }
    }

    #[OA\Post(path: '/login/smsCode', summary: '通过手机短信验证码登录', tags: ['登录'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginSmsRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function smsCode(LoginSmsRequest $request): JsonResponse
    {
        try {
            $formData = $request->validated();

            // 校验短信验证码
            $smsCode = Cache::get(Constant::SMS_CACHE_PREFIX.$formData['mobile']);
            if ($smsCode !== $formData['code']) {
                return $this->error('短信验证码不正确');
            }

            $userService = new UserService();
            $user = $userService->findByMobile($formData['mobile'], UserStatusEnum::Normal);
            $token = $user->createToken('token')->plainTextToken;

            $response = new LoginResponse();
            $response->setToken($token);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e->getMessage());

            return $this->error('短信登录错误');
        }
    }
}
