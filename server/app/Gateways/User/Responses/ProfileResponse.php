<?php

declare(strict_types=1);

namespace App\Gateways\User\Responses;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProfileResponse')]
class ProfileResponse
{
    use ArrayObject;
}
