<?php

namespace App\Modules\Auth\Services;

use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * 手机号 + 密码登录
     *
     * @return array{user: User, token: string}
     */
    public function loginByPhone(string $phone, string $password, string $deviceName = 'api'): array
    {
        $user = User::where('phone', $phone)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['手机号或密码错误。'],
            ]);
        }

        if (! $user->isEnabled()) {
            throw ValidationException::withMessages([
                'phone' => ['账号已被禁用。'],
            ]);
        }

        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * 邮箱 + 密码登录
     *
     * @return array{user: User, token: string}
     */
    public function loginByEmail(string $email, string $password, string $deviceName = 'api'): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['邮箱或密码错误。'],
            ]);
        }

        if (! $user->isEnabled()) {
            throw ValidationException::withMessages([
                'email' => ['账号已被禁用。'],
            ]);
        }

        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * 用户注册
     *
     * @param  array<string, mixed>  $data
     * @return array{user: User, token: string}
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'] ?? ($data['nickname'] ?? $data['phone']),
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'nickname' => $data['nickname'] ?? null,
            'password' => $data['password'],
            'status' => 1,
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * 退出登录
     */
    public function logout(User $user): void
    {
        $token = $user->currentAccessToken();

        if ($token !== null) {
            $token->delete();
        }
    }

    /**
     * 退出所有设备
     */
    public function logoutAllDevices(User $user): void
    {
        $user->tokens()->delete();
    }
}
