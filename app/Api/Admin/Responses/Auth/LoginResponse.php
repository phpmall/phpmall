<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\Auth;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: LoginResponse::class)]
class LoginResponse
{
    use DTOHelper;

    #[OA\Property(property: 'token', description: 'token', type: 'string')]
    private string $token;

    #[OA\Property(property: 'expires', description: 'expires', type: 'integer')]
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
