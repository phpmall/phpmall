<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\Auth\LoginRequest;
use App\Api\Admin\Responses\Auth\LoginResponse;
use App\Enums\ErrorEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AuthController extends Controller
{
    #[OA\Post(path: '/auth/login', summary: '管理员登录接口', tags: ['认证模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: LoginResponse::class))]
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $request->authenticate();

            $expiresAt = now()->addDays(7);
            $token = $request->user(RoleEnum::Admin->value)->createToken('webapi', ['*'], $expiresAt);

            $loginResponse = new LoginResponse;
            $loginResponse->setToken($token->plainTextToken);
            $loginResponse->setExpires($expiresAt->timestamp);

            return $this->success($loginResponse->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e->getMessage(), ['exception' => $e]);

            return $this->error(ErrorEnum::LOGIN_FAIL);
        }
    }
}
