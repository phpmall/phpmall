<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Responses\AdPosition;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdPositionDestroyResponse')]
class AdPositionDestroyResponse
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
