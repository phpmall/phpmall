<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Enums\UserStatusEnum;
use App\Bundles\User\Services\Input\LoginInput;
use App\Exceptions\CustomException;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginService
{
    /**
     * 根据用户名和密码登录
     *
     * @throws CustomException
     */
    public function login(LoginInput $loginInput): UserModel
    {
        $user = $this->user($loginInput->getUsername(), UserStatusEnum::Normal);
        if (is_null($user)) {
            throw new CustomException('用户信息不存在');
        }

        // 校验密码
        $password = $loginInput->getPassword().$user->password_salt;
        if (! Hash::check($password, $user->getAuthPassword())) {
            throw new CustomException('用户登录密码不正确');
        }

        // 更新密码
        $user->password_salt = Str::random();
        $user->password = Hash::make($loginInput->getPassword().$user->password_salt);
        $user->save();

        // 记录日志 TODO

        return $user;
    }

    /**
     * 使用用户ID登录
     */
    public function loginUsingId(int $userId, string $guard, bool $rememberMe): void
    {
        session('auth_'.$guard, $userId);

        // 记住登录
        if ($rememberMe) {
            cookie($guard.'_remember', $userId, 30 * 24 * 3600);
        }
    }

    /**
     * 获取登录用户
     */
    private function user(string $username, UserStatusEnum $status): ?UserModel
    {
        $userService = new UserService();
        if (is_email($username)) {
            return $userService->findByEmail($username, $status);
        } elseif (is_mobile($username)) {
            return $userService->findByMobile($username, $status);
        }

        return $userService->findByUsername($username, $status);
    }
}
