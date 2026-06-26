<?php

declare(strict_types=1);

namespace App\Api\User\Responses\Security;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SecurityUpdateResponse')]
class SecurityUpdateResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'success', description: '是否成功:0否，1是', type: 'integer')]
    private int $success;

    #[OA\Property(property: 'message', description: '操作结果消息', type: 'string')]
    private string $message;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string', format: 'date-time')]
    private string $updatedAt;

    public function getSuccess(): int
    {
        return $this->success;
    }

    public function setSuccess(int $success): void
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

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
