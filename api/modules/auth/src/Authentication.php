<?php

declare(strict_types=1);

namespace Juling\Auth;

class Authentication
{
    private JWT $jwt;

    public function __construct()
    {
        $this->jwt = new JWT(config('jwt'));
    }

    /**
     * 获取有效荷载
     */
    public function getPayload(TokenExtractorInterface $tokenExtractor): array
    {
        $token = $tokenExtractor->extractToken();

        return $this->jwt->parse($token);
    }

    /**
     * 根据token获取有效荷载
     */
    public function getPayloadByToken(string $token): array
    {
        return $this->jwt->parse($token);
    }

    /**
     * 创建token
     */
    public function createToken(array $payload): string
    {
        return $this->jwt->create($payload);
    }
}
