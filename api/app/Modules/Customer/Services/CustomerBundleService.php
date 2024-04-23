<?php

declare(strict_types=1);

namespace App\Modules\Customer\Services;

use App\Modules\User\Enums\UserSocialiteTypeEnum;
use App\Modules\User\Enums\UserStatusEnum;
use App\Models\User;
use App\Services\UserService;

class CustomerBundleService extends UserService
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
        $userSocialiteService = new UserSocialiteService();
        $userSocialite = $userSocialiteService->getOne([
            ['type', '=', UserSocialiteTypeEnum::Username->value],
            ['identifier', '=', $username],
            ['status', '=', $status->value],
        ]);

        return $this->findById($userSocialite['user_id'], $status);
    }

    /**
     * 根据手机号码查询用户
     *
     * @throws CustomException
     */
    public function findByMobile(string $mobile, UserStatusEnum $status): User
    {
        return $this->getUser('mobile', $mobile, $status);
    }

    /**
     * 根据电子邮箱查询用户
     *
     * @throws CustomException
     */
    public function findByEmail(string $email, UserStatusEnum $status): User
    {
        $userSocialiteService = new UserSocialiteService();
        $userSocialite = $userSocialiteService->getOne([
            ['type', '=', UserSocialiteTypeEnum::Email->value],
            ['identifier', '=', $email],
            ['status', '=', $status->value],
        ]);

        return $this->findById($userSocialite['user_id'], $status);
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
        $user = User::where([
            [$type, '=', $val],
            ['status', '=', $status->value],
        ])->first();

        if (empty($user)) {
            throw new CustomException('没有找到用户');
        }

        return $user;
    }
}
