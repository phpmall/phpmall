<?php

declare(strict_types=1);

namespace App\Gateways\User\Controllers;

use App\Bundles\Foundation\Controllers\Controller;
use App\Bundles\Foundation\Enums\GuardTypeEnum;
use App\Bundles\User\Services\Input\LoginInput;
use App\Bundles\User\Services\LoginService;
use App\Exceptions\CustomException;
use App\Gateways\User\Requests\Auth\LoginRequest;
use App\Gateways\User\Responses\LoginResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Throwable;

class AuthController extends Controller
{
    #[OA\Post(path: '/user/login', summary: '登录操作', tags: ['认证管理'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $request->validated();

            $loginInput = new LoginInput();
            $loginInput->setUsername($request->post('username'));
            $loginInput->setPassword($request->post('password'));
            $loginInput->setCaptcha($request->post('captcha'));
            $loginInput->setUuid($request->post('uuid'));

            $loginService = new LoginService();
            $user = $loginService->login($loginInput);
            $token = $user->createToken('token', ['role:'.GuardTypeEnum::User->value], now()->addMonths())->plainTextToken;

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

}
