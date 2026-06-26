<?php

declare(strict_types=1);

namespace App\Api\Supplier\Responses\Message;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierMessageMarkReadResponse')]
class MessageMarkReadResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'success', description: '操作结果', type: 'boolean')]
    private bool $success;

    #[OA\Property(property: 'marked_count', description: '标记已读数量', type: 'integer')]
    private int $markedCount;

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function getMarkedCount(): int
    {
        return $this->markedCount;
    }

    public function setMarkedCount(int $markedCount): void
    {
        $this->markedCount = $markedCount;
    }
}
