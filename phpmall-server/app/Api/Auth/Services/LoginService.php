<?php

declare(strict_types=1);

namespace App\Api\Auth\Services;

use App\Api\Auth\Services\Input\LoginViaMobileInput;
use App\Bundles\User\Enums\UserStatusEnum;
use App\Bundles\User\Services\UserService;
use App\Foundation\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService extends UserService
{
    /**
     * 根据手机号码和密码登录
     *
     * @throws CustomException
     */
    public function mobile(LoginViaMobileInput $loginInput): User
    {
        $user = $this->user($loginInput->getMobile(), UserStatusEnum::Normal);
        if (is_null($user)) {
            throw new CustomException('用户信息不存在');
        }

        // 校验密码
        $password = $loginInput->getPassword(); // .$user->password_salt;
        if (! Hash::check($password, $user->getAuthPassword())) {
            throw new CustomException('管理员登录密码不正确');
        }

        // 更新密码
        // $user->password_salt = Str::random();
        // $user->password = Hash::make($loginInput->getPassword().$user->password_salt);
        // $user->save();

        // 记录日志 TODO

        return $user;
    }

    /**
     * 获取登录用户
     */
    private function user(string $username, UserStatusEnum $status): ?User
    {
        if (is_email($username)) {
            return $this->findByEmail($username, $status);
        } elseif (is_mobile($username)) {
            return $this->findByMobile($username, $status);
        }

        return $this->findByUsername($username, $status);
    }
}
