<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Carbon;
use App\Foundation\JWT\Authentication;
use App\Foundation\JWT\BearerTokenExtractor;

class JWTHelper
{
    /**
     * 用户JWT参数名
     */
    const JWT_USER_ID = 'user_id';

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
