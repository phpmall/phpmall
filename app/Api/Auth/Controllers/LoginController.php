<?php

declare(strict_types=1);

namespace App\Api\Auth\Controllers;

use App\Api\Auth\Requests\Login\LoginMobileRequest;
use App\Api\Auth\Requests\Login\LoginRequest;
use App\Api\Auth\Requests\Login\LoginSmsRequest;
use App\Api\Auth\Responses\LoginResponse;
use App\Api\Auth\Services\AuthService;
use App\Api\Auth\Services\Input\LoginViaMobileInput;
use App\Api\Auth\Services\LoginService;
use App\Exceptions\CustomException;
use App\Foundation\Constants\Constant;
use App\Services\UserService;
use Exception;
use Juling\Captcha\Captcha;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Throwable;

class LoginController extends BaseController
{
    #[OA\Post(path: '/api/auth/login/mobile', summary: '通过手机号和密码登录', tags: ['认证管理'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function mobile(LoginRequest $request): JsonResponse
    {
        try {
            $request->validated();

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
        } catch (CustomException $e) {
            return $this->error($e->getMessage());
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return $this->error($e->getMessage());
        }
    }

    #[OA\Post(path: '/api/login/mobile2', summary: '通过手机号和密码登录', tags: ['登录'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginMobileRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function mobile2(LoginMobileRequest $request): JsonResponse
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
                    Constant::JWT_USER_ID => Auth::id(),
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

    #[OA\Post(path: '/api/login/mobile', summary: '通过手机短信验证码登录', tags: ['登录'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginSmsRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function mobile3(LoginMobileRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // 校验短信验证码
            $smsCode = Cache::get(Constant::SMS_CACHE_PREFIX.$data['mobile']);
            if ($smsCode !== $data['code']) {
                return $this->error('短信验证码不正确');
            }

            $userService = new UserService();
            $userOutput = $userService->findOneByMobile($data['mobile']);

            $authService = new AuthService();
            $token = $authService->createToken([
                Constant::JWT_USER_ID => $userOutput->getId(),
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
