<?php

declare(strict_types=1);

namespace App\Foundation\Http\Responses;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OptionResponse')]
class OptionResponse
{
    use ArrayObject;

    #[OA\Property(property: 'name', description: '名称', type: 'string', example: 'name')]
    private string $name;

    #[OA\Property(property: 'val', description: '值', type: 'integer', example: 1)]
    private int $val;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setVal(int $val): void
    {
        $this->val = $val;
    }
}
