<?php

declare(strict_types=1);

namespace App\Foundation\Region\Responses;

use App\Foundation\DevTools\stubs\app\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RegionResponse')]
class RegionResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: '地区ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '地区名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'first_letter', description: '地区名称首字母', type: 'string')]
    private string $firstLetter;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setFirstLetter(string $firstLetter): void
    {
        $this->firstLetter = $firstLetter;
    }
}
