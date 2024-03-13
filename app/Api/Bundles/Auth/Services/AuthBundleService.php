<?php

declare(strict_types=1);

namespace App\Bundles\Auth\Services;

use App\Auth\Services\Constant;
use App\Bundles\Auth\Services\Input\LoginInput;
use App\Bundles\UMS\Enums\UserStatusEnum;
use App\Bundles\UMS\Services\UserBundleService;
use App\Foundation\Infra\DevTools\stubs\app\Exceptions\CustomException;
use App\Models\User;
use App\Foundation\Infra\DevTools\stubs\app\Support\JWTHelper;
use Illuminate\Support\Facades\Hash;

class AuthBundleService extends UserBundleService
{
    /**
     * 返回用户数据
     *
     * @throws CustomException
     */
    public function auth($token = null): array
    {
        $JWTService = new JWTHelper();
        if (is_null($token)) {
            $payload = $JWTService->getPayloadByBearer();
        } else {
            $payload = $JWTService->getPayloadByToken($token);
        }

        if (isset($payload[Constant::JWT_USER_ID])) {
            $user = $this->findById($payload[Constant::JWT_USER_ID], UserStatusEnum::Normal);

            return $user->toArray();
        }

        return [];
    }

    /**
     * 根据手机号码和密码登录
     *
     * @throws CustomException
     */
    public function login(LoginInput $input): User
    {
        $user = $this->user($input->getUsername(), UserStatusEnum::Normal);

        // 校验密码
        if (! Hash::check($input->getPassword(), $user->getAuthPassword())) {
            throw new CustomException('管理员登录密码不正确');
        }

        // 记录日志 TODO

        return $user;
    }

    /**
     * 获取登录用户
     *
     * @throws CustomException
     */
    private function user(string $username, UserStatusEnum $status): User
    {
        if (is_email($username)) {
            return $this->findByEmail($username, $status);
        } elseif (is_mobile($username)) {
            return $this->findByMobile($username, $status);
        }

        return $this->findByUsername($username, $status);
    }
}
