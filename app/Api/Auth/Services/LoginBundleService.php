<?php

declare(strict_types=1);

namespace App\Api\Auth\Services;

use App\Bundles\User\Enums\UserStatusEnum;
use App\Bundles\User\Services\UserBundleService;
use App\Exceptions\CustomException;
use App\Services\JWTService;
use App\Http\Controllers\Auth\Services\Input\LoginInput;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginBundleService extends UserBundleService
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

        $userId = $payload[JWTService::JWT_USER_ID] ?? 0;
        if ($userId > 0) {
            return $this->getOneById($userId);
        }

        return [];
    }

    /**
     * 根据手机号码和密码登录
     *
     * @throws CustomException
     */
    public function handle(LoginInput $loginInput): User
    {
        $user = $this->getUser($loginInput->getUsername(), UserStatusEnum::Normal);

        // 校验密码
        $password = $loginInput->getPassword();
        if (! Hash::check($password, $user->getAuthPassword())) {
            throw new CustomException('用户登录密码不正确');
        }

        return $user;
    }

    /**
     * 获取登录用户
     *
     * @throws CustomException
     */
    private function getUser(string $username, UserStatusEnum $status): User
    {
        if (is_email($username)) {
            return $this->findByEmail($username, $status);
        } elseif (is_mobile($username)) {
            return $this->findByMobile($username, $status);
        }

        return $this->findByUsername($username, $status);
    }
}
