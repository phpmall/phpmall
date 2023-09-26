<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Enums\UserStatusEnum;
use App\Models\UserModel;

class UserService extends \App\Services\UserService
{
    /**
     * 根据用户名查询用户
     */
    public function findById(int $id, UserStatusEnum $status): ?UserModel
    {
        return $this->getUser('id', $id, $status);
    }

    /**
     * 根据用户名查询用户
     */
    public function findByUsername(string $username, UserStatusEnum $status): ?UserModel
    {
        return $this->getUser('username', $username, $status);
    }

    /**
     * 根据手机号码查询用户
     */
    public function findByMobile(string $mobile, UserStatusEnum $status): ?UserModel
    {
        $userAuthService = new UserAuthService();
        $userAuth = $userAuthService->find('mobile', $mobile);
        if ($userAuth->isEmpty()) {
            return null;
        }

        return $this->findById($userAuth->user_id, $status);
    }

    /**
     * 根据电子邮箱查询用户
     */
    public function findByEmail(string $email, UserStatusEnum $status): ?UserModel
    {
        $userAuthService = new UserAuthService();
        $userAuth = $userAuthService->find('email', $email);
        if ($userAuth->isEmpty()) {
            return null;
        }

        return $this->findById($userAuth->user_id, $status);
    }

    /**
     * 根据用户remember token查询用户
     */
    public function findByRememberToken(string $remember_token, UserStatusEnum $status): ?UserModel
    {
        return $this->getUser('remember_token', $remember_token, $status);
    }

    /**
     * 根据用户reset token查询用户
     */
    public function findByResetToken(string $reset_token, UserStatusEnum $status): ?UserModel
    {
        return $this->getUser('reset_token', $reset_token, $status);
    }

    /**
     * 查询模型
     */
    private function getUser(string $type, string $val, UserStatusEnum $status): ?UserModel
    {
        return $this->getRepository()->model()->where([
            [$type, '=', $val],
            ['status', '=', $status->value],
        ])->first();
    }
}
