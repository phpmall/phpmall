<?php

declare(strict_types=1);

namespace App\Gateways\Auth\Services;

use App\Constants\GlobalConst;
use App\Services\UserService;
use Illuminate\Support\Carbon;
use Laractl\Auth\Authentication;
use Laractl\Auth\BearerTokenExtractor;

class AuthService
{
    /**
     * 返回用户数据
     */
    public function auth($token = null): array
    {
        if (is_null($token)) {
            $payload = $this->getPayloadByBearer();
        } else {
            $payload = $this->getPayloadByToken($token);
        }

        if (isset($payload[GlobalConst::JWT_USER_ID])) {
            $userService = new UserService();
            $userOutput = $userService->getRepository()->findOneByIdReturnUser($payload[GlobalConst::JWT_USER_ID]);

            return $userOutput->toArray();
        }

        return [];
    }

    /**
     * 创建JWT参数
     */
    public function createToken(array $body, int $expire = 0): string
    {
        $authentication = new Authentication();

        $payload = config('jwt.payload');
        $payload['body'] = $body;
        if ($expire > 0) {
            $payload['exp'] = Carbon::now()->timestamp + $expire;
        }

        return $authentication->createToken($payload);
    }

    /**
     * 根据Token头获取JWT参数
     */
    public function getPayloadByBearer(): array
    {
        $authentication = new Authentication();

        $bearerTokenExtractor = new BearerTokenExtractor();
        $payload = $authentication->getPayload($bearerTokenExtractor);

        return (array) $payload['body'] ?? [];
    }

    /**
     * 根据Token头获取JWT参数
     */
    public function getPayloadByToken(string $token): array
    {
        $authentication = new Authentication();

        $payload = $authentication->getPayloadByToken($token);

        return (array) $payload['body'] ?? [];
    }
}
