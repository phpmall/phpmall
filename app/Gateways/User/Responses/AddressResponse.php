<?php

declare(strict_types=1);

namespace App\Gateways\User\Responses;

use Focite\Builder\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AddressResponse')]
class AddressResponse
{
    use ArrayObject;

}
