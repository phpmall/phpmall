<?php

declare(strict_types=1);

namespace App\Gateways\User\Responses;

use Focite\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'LoginResponse')]
class LoginResponse
{
    use ArrayObject;

    #[OA\Property(property: 'token', description: 'JWT', type: 'string')]
    private string $token;

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
