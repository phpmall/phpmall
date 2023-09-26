<?php

declare(strict_types=1);

namespace App\Bundles\User\Services;

use App\Enums\UserAuthEnum;
use App\Models\UserAuth;

class UserAuthService
{
    /**
     * 根据条件查询用户
     */
    public function find(string $type, string $identifier)
    {
        $condition = [
            'type' => $type,
            'identifier' => $identifier,
            'status' => UserAuthEnum::STATUS_OK,
        ];

        return UserAuth::where($condition)->findOrEmpty();
    }
}
