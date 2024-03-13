<?php

declare(strict_types=1);

namespace App\Bundles\UMS\Services;

use App\Bundles\UMS\Enums\UserSocialiteTypeEnum;
use App\Bundles\UMS\Enums\UserStatusEnum;
use App\Foundation\Infra\DevTools\stubs\app\Exceptions\CustomException;
use App\Models\User;
use App\Services\AuthenticationService;
use App\Services\UserService;

class UserBundleService extends UserService
{
    /**
     * 根据用户名查询用户
     *
     * @throws CustomException
     */
    public function findById(int $id, UserStatusEnum $status): User
    {
        return $this->getUser('id', $id, $status);
    }

    /**
     * 根据用户名查询用户
     *
     * @throws CustomException
     */
    public function findByUsername(string $username, UserStatusEnum $status): User
    {
        return $this->getUser('username', $username, $status);
    }

    /**
     * 根据手机号码查询用户
     *
     * @throws CustomException
     */
    public function findByMobile(string $mobile, UserStatusEnum $status): User
    {
        $authenticationService = new AuthenticationService();
        $authentication = $authenticationService->getOne([
            ['type', '=', UserSocialiteTypeEnum::Mobile->value],
            ['identifier', '=', $mobile],
            ['status', '=', $status->value],
        ]);

        if (empty($authentication)) {
            throw new CustomException('用户信息不存在');
        }

        return $this->findById($authentication['user_id'], $status);
    }

    /**
     * 根据电子邮箱查询用户
     *
     * @throws CustomException
     */
    public function findByEmail(string $email, UserStatusEnum $status): User
    {
        $authenticationService = new AuthenticationService();
        $authentication = $authenticationService->getOne([
            ['type', '=', UserSocialiteTypeEnum::Email->value],
            ['identifier', '=', $email],
            ['status', '=', $status->value],
        ]);

        if (empty($authentication)) {
            throw new CustomException('用户信息不存在');
        }

        return $this->findById($authentication['user_id'], $status);
    }

    /**
     * 根据用户remember token查询用户
     *
     * @throws CustomException
     */
    public function findByRememberToken(string $remember_token, UserStatusEnum $status): User
    {
        return $this->getUser('remember_token', $remember_token, $status);
    }

    /**
     * 查询模型
     *
     * @throws CustomException
     */
    private function getUser(string $type, int|string $val, UserStatusEnum $status): User
    {
        $user = $this->getRepository()->model()->where([
            [$type, '=', $val],
            ['status', '=', $status->value],
        ])->first();

        if (empty($user)) {
            throw new CustomException('用户信息不存在');
        }

        return $user;
    }
}
