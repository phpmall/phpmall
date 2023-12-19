<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Services;

use App\Bundles\Admin\Enums\AdminUserStatusEnum;
use App\Bundles\Admin\Services\Input\LoginInput;
use App\Foundation\Exceptions\CustomException;
use App\Models\AdminUserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginService
{
    /**
     * 根据用户名和密码登录
     *
     * @throws CustomException
     */
    public function login(LoginInput $loginInput): AdminUserModel
    {
        $adminUser = $this->user($loginInput->getUsername(), AdminUserStatusEnum::Normal);
        if (is_null($adminUser)) {
            throw new CustomException('管理员信息不存在');
        }

        // 校验密码
        $password = $loginInput->getPassword().$adminUser->password_salt;
        if (! Hash::check($password, $adminUser->getAuthPassword())) {
            throw new CustomException('管理员登录密码不正确');
        }

        // 更新密码
        $adminUser->password_salt = Str::random();
        $adminUser->password = Hash::make($loginInput->getPassword().$adminUser->password_salt);
        $adminUser->save();

        // 记录日志 TODO

        return $adminUser;
    }

    /**
     * 获取登录用户
     */
    private function user(string $username, AdminUserStatusEnum $status): ?AdminUserModel
    {
        $userService = new AdminUserService();
        if (is_email($username)) {
            return $userService->findByEmail($username, $status);
        } elseif (is_mobile($username)) {
            return $userService->findByMobile($username, $status);
        }

        return $userService->findByUsername($username, $status);
    }
}
