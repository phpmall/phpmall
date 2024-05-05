<?php

declare(strict_types=1);

namespace App\Bundles\User\Responses;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AddressResponse')]
class AddressResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: '编号', type: 'integer', example: 1), ]
    private int $id;

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
