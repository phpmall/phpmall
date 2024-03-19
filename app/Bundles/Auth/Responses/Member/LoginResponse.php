<?php

declare(strict_types=1);

namespace App\Bundles\Auth\Responses\Member;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'LoginResponse')]
class LoginResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'token', description: 'token', type: 'string')]
    private string $token;
}
