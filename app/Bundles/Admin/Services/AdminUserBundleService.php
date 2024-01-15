<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Services;

use App\Bundles\Admin\Enums\AdminUserStatusEnum;
use App\Models\AdminUser;

class AdminUserBundleService extends \App\Services\AdminUserService
{
    /**
     * 根据用户名查询用户
     */
    public function findByUsername(string $username, AdminUserStatusEnum $status): ?AdminUser
    {
        return $this->getAdminUser('username', $username, $status);
    }

    /**
     * 根据手机号码查询用户
     */
    public function findByMobile(string $mobile, AdminUserStatusEnum $status): ?AdminUser
    {
        return $this->getAdminUser('mobile', $mobile, $status);
    }

    /**
     * 根据电子邮箱查询用户
     */
    public function findByEmail(string $email, AdminUserStatusEnum $status): ?AdminUser
    {
        return $this->getAdminUser('email', $email, $status);
    }

    /**
     * 查询模型
     */
    private function getAdminUser(string $type, string $val, AdminUserStatusEnum $status): ?AdminUser
    {
        return $this->getRepository()->model()->where([
            [$type, '=', $val],
            ['status', '=', $status->value],
        ])->first();
    }
}
