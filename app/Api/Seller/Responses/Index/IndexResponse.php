<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Index;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerIndexResponse')]
class IndexResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'message', description: '欢迎信息', type: 'string')]
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
