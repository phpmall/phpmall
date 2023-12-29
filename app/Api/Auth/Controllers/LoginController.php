<?php

declare(strict_types=1);

namespace App\Api\Auth\Controllers;

use App\Api\Auth\Requests\Login\LoginMobileRequest;
use App\Api\Auth\Requests\Login\LoginRequest;
use App\Api\Auth\Requests\Login\LoginSmsRequest;
use App\Api\Auth\Responses\LoginResponse;
use App\Api\Auth\Services\Input\LoginViaMobileInput;
use App\Api\Auth\Services\LoginService;
use App\Bundles\Sms\Services\SmsService;
use App\Bundles\User\Enums\UserStatusEnum;
use App\Bundles\User\Services\UserService;
use App\Foundation\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Juling\Captcha\Captcha;
use OpenApi\Attributes as OA;
use Throwable;

class LoginController extends BaseController
{
    #[OA\Post(path: '/login', summary: '通过用户名和密码登录', tags: ['认证管理'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function index(LoginRequest $request): JsonResponse
    {
        try {
            $formData = $request->validated();

            $captchaService = new Captcha();
            if (! $captchaService->check($formData['uuid'], $formData['captcha'])) {
                throw new CustomException('图片验证码输入错误');
            }

            $credentials = [
                'username' => $formData['username'],
                'password' => $formData['password'],
            ];
            $remember = $formData['remember'] ?? 'off';

            if (Auth::attempt($credentials, $remember === 'on')) {
                $token = Auth::user()->createToken('token')->plainTextToken;

                $response = new LoginResponse();
                $response->setToken($token);

                return $this->success($response->toArray());
            }

            throw new CustomException('用户名或密码错误');
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e->getMessage());

            return $this->error($e->getMessage());
        }
    }

    #[OA\Post(path: '/login/mobile', summary: '通过手机号和密码登录', tags: ['认证管理'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginMobileRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function mobile(LoginMobileRequest $request): JsonResponse
    {
        try {
            $formData = $request->validated();

            $captchaService = new Captcha();
            if (! $captchaService->check($formData['uuid'], $formData['captcha'])) {
                throw new CustomException('图片验证码输入错误');
            }

            $loginInput = new LoginViaMobileInput();
            $loginInput->setMobile($formData['mobile']);
            $loginInput->setPassword($formData['password']);
            $loginInput->setCaptcha($formData['captcha']);
            $loginInput->setUuid($formData['uuid']);

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
            $smsService = new SmsService();
            if (! $smsService->checkCode($formData['mobile'], $formData['code'])) {
                throw new CustomException('短信验证码不正确');
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
