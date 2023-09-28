<?php

declare(strict_types=1);

namespace App\Bundles\User\Responses;

use Focite\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProfileResponse')]
class ProfileResponse
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '编号', type: 'integer', example: 1), ]
    private int $id;

    #[OA\Property(property: 'name', description: '名称', type: 'string')]
    private string $name;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
