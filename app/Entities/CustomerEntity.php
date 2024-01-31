<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CustomerEntity')]
class CustomerEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
