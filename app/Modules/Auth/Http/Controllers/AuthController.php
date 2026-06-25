<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Http\Requests\LoginRequest;
use App\Modules\Auth\Http\Requests\RegisterRequest;
use App\Modules\Auth\Services\AuthService;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'code' => 0,
            'message' => '注册成功',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
            ],
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! empty($credentials['phone'])) {
            $result = $this->authService->loginByPhone(
                $credentials['phone'],
                $credentials['password'],
                $credentials['device_name'] ?? 'api'
            );
        } else {
            $result = $this->authService->loginByEmail(
                $credentials['email'],
                $credentials['password'],
                $credentials['device_name'] ?? 'api'
            );
        }

        return response()->json([
            'code' => 0,
            'message' => '登录成功',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $this->authService->logout($user);

        return response()->json([
            'code' => 0,
            'message' => '退出成功',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        return response()->json([
            'code' => 0,
            'data' => $user->load('roles.permissions'),
        ]);
    }

    private function resolveUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401, '未登录');
        }

        return $user;
    }
}
