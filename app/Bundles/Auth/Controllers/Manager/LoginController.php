<?php

declare(strict_types=1);

namespace App\Bundles\Auth\Controllers\Manager;

use App\API\Common\Controllers\BaseController;
use App\Bundles\Auth\Requests\Login\LoginRequest;
use App\Bundles\Auth\Requests\Login\LoginSmsRequest;
use App\Bundles\Auth\Responses\LoginResponse;
use App\Bundles\Auth\Services\Input\LoginInput;
use App\Bundles\Auth\Services\LoginBundleService;
use App\Bundles\SMS\Services\SmsBundleService;
use App\Bundles\User\Enums\UserStatusEnum;
use App\Bundles\User\Services\UserBundleService;
use App\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Throwable;

class LoginController extends BaseController
{
    #[OA\Post(path: '/login', summary: '通过用户名和密码登录', tags: ['登录'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function index(LoginRequest $request): JsonResponse
    {
        $formData = $request->validated();

        try {
            $loginInput = new LoginInput();
            $loginInput->setUsername($formData['username']);
            $loginInput->setPassword($formData['password']);
            $loginInput->setCaptcha($formData['captcha']);
            $loginInput->setUuid($formData['uuid']);

            $loginService = new LoginBundleService();
            $user = $loginService->handle($loginInput);

            $routeMiddlewares = $request->route()->middleware();
            if (in_array('web', $routeMiddlewares)) {
                $remember = $formData['remember'] ?? 'off';

                Auth::loginUsingId($user->getAuthIdentifier(), $remember);
            }

            $response = new LoginResponse();
            $token = $user->createToken('token')->plainTextToken;
            $response->setToken($token);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e->getMessage());

            return $this->error('用户登录错误');
        }
    }

    #[OA\Post(path: '/login/smsCode', summary: '通过手机短信验证码登录', tags: ['登录'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginSmsRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function smsCode(LoginSmsRequest $request): JsonResponse
    {
        try {
            $formData = $request->validated();

            $smsService = new SmsBundleService();
            if (! $smsService->checkCode($formData['mobile'], $formData['code'])) {
                throw new CustomException('短信验证码不正确');
            }

            $userService = new UserBundleService();
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
