<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Auth;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'LoginResponse')]
class LoginResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'token', description: '访问令牌', type: 'string')]
    private string $token;

    #[OA\Property(property: 'expires', description: '令牌过期时间戳', type: 'integer')]
    private int $expires;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getExpires(): int
    {
        return $this->expires;
    }

    public function setExpires(int $expires): void
    {
        $this->expires = $expires;
    }
}
