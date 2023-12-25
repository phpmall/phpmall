<?php

declare(strict_types=1);

namespace App\Api\Auth\Services;

use App\Foundation\Constants\Constant;
use App\Foundation\Services\JWTService;
use App\Services\UserService;

class AuthService extends UserService
{
    /**
     * 返回用户数据
     */
    public function auth($token = null): array
    {
        $JWTService = new JWTService();

        if (is_null($token)) {
            $payload = $JWTService->getPayloadByBearer();
        } else {
            $payload = $JWTService->getPayloadByToken($token);
        }

        if (isset($payload[Constant::JWT_USER_ID])) {
            return $this->getOneById($payload[Constant::JWT_USER_ID]);
        }

        return [];
    }
}
