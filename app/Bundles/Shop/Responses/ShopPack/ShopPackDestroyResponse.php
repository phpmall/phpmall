<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Responses\ShopPack;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopPackDestroyResponse')]
class ShopPackDestroyResponse
{
    use DTOHelper;

    #[OA\Property(property: 'status', description: '状态:1成功，2失败', type: 'integer')]
    private int $status;

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
