<?php

declare(strict_types=1);

namespace App\Bundles\Auth\Responses;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'LoginResponse')]
class LoginResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'token', description: '用户JSON Web Token凭证', type: 'string', example: '123456'), ]
    private string $token;

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
