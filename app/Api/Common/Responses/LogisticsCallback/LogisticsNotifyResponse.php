<?php

declare(strict_types=1);

namespace App\Api\Common\Responses\LogisticsCallback;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommonLogisticsNotifyResponse')]
class LogisticsNotifyResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'success', description: '处理结果', type: 'boolean')]
    private bool $success;

    #[OA\Property(property: 'message', description: '处理消息', type: 'string')]
    private string $message;

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
