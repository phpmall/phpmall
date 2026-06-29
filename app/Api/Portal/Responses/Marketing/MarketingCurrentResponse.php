<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Marketing;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalMarketingCurrentResponse')]
class MarketingCurrentResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'message', description: '当前营销活动信息', type: 'string')]
    private string $message;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
