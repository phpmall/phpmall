<?php

declare(strict_types=1);

namespace App\Bundles\Auth\Controllers\Admin;

use App\Api\Common\Controllers\BaseController;
use App\Bundles\Auth\Requests\Admin\Login\LoginRequest;
use App\Bundles\Auth\Requests\Admin\Login\MobileLoginRequest;
use App\Bundles\Auth\Responses\LoginResponse;
use App\Bundles\Passport\Services\AuthBundleService;
use App\Bundles\Auth\Services\Input\LoginInput;
use App\Foundation\Infra\DevTools\stubs\app\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Throwable;

class LoginController extends BaseController
{
    #[OA\Post(path: '/login', summary: '通过用户名和密码登录', tags: ['认证管理'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function index(LoginRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $loginInput = new LoginInput();
            $loginInput->setUsername($request->post('username'));
            $loginInput->setPassword($request->post('password'));
            $loginInput->setCaptcha($request->post('captcha'));
            $loginInput->setUuid($request->post('uuid'));

            $authService = new AuthBundleService();
            $user = $authService->login($loginInput);
            $token = $user->createToken('token')->plainTextToken;

            $loginResponse = new LoginResponse();
            $loginResponse->setToken($token);

            return $this->success($loginResponse->toArray());
        } catch (CustomException $e) {
            return $this->error($e->getMessage());
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return $this->error('通过手机号和密码登录错误');
        }
    }

    #[OA\Post(path: '/login/mobile', summary: '通过手机号和密码登录', tags: ['认证管理'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MobileLoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function mobile(MobileLoginRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $loginInput = new LoginInput();
            $loginInput->setUsername($request->post('mobile'));
            $loginInput->setPassword($request->post('password'));
            $loginInput->setCaptcha($request->post('captcha'));
            $loginInput->setUuid($request->post('uuid'));

            $authService = new AuthBundleService();
            $user = $authService->login($loginInput);
            $token = $user->createToken('token')->plainTextToken;

            $loginResponse = new LoginResponse();
            $loginResponse->setToken($token);

            return $this->success($loginResponse->toArray());
        } catch (CustomException $e) {
            return $this->error($e->getMessage());
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return $this->error('通过手机号和密码登录错误');
        }
    }
}
