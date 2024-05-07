<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Bundles\User\Enums\UserSocialiteTypeEnum;
use App\Bundles\User\Enums\UserStatusEnum;
use App\Models\User;
use App\Services\UserService as BaseUserService;
use App\Services\UserSocialiteService;

class UserService extends BaseUserService
{
    /**
     * 根据用户名查询用户
     */
    public function findById(int $id, UserStatusEnum $status): ?User
    {
        return $this->getUser('id', $id, $status);
    }

    /**
     * 根据用户名查询用户
     */
    public function findByUsername(string $username, UserStatusEnum $status): ?User
    {
        return $this->getUser('username', $username, $status);
    }

    /**
     * 根据手机号码查询用户
     */
    public function findByMobile(string $mobile, UserStatusEnum $status): ?User
    {
        $userSocialiteService = new UserSocialiteService();
        $userSocialite = $userSocialiteService->getOne([
            ['type', '=', UserSocialiteTypeEnum::Mobile->value],
            ['identifier', '=', $mobile],
            ['status', '=', $status->value],
        ]);

        if (empty($userSocialite)) {
            return null;
        }

        return $this->findById($userSocialite['user_id'], $status);
    }

    /**
     * 根据电子邮箱查询用户
     */
    public function findByEmail(string $email, UserStatusEnum $status): ?User
    {
        $userSocialiteService = new UserSocialiteService();
        $userSocialite = $userSocialiteService->getOne([
            ['type', '=', UserSocialiteTypeEnum::Email->value],
            ['identifier', '=', $email],
            ['status', '=', $status->value],
        ]);

        if (empty($userSocialite)) {
            return null;
        }

        return $this->findById($userSocialite['user_id'], $status);
    }

    /**
     * 根据用户remember token查询用户
     */
    public function findByRememberToken(string $remember_token, UserStatusEnum $status): ?User
    {
        return $this->getUser('remember_token', $remember_token, $status);
    }

    /**
     * 查询模型
     */
    private function getUser(string $type, int|string $val, UserStatusEnum $status): ?User
    {
        return $this->getRepository()->model()->where([
            [$type, '=', $val],
            ['status', '=', $status->value],
        ])->first();
    }
}
