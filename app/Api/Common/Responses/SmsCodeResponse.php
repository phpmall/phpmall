<?php

declare(strict_types=1);

namespace App\Api\Common\Responses;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SmsCodeResponse')]
class SmsCodeResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'code_id', description: '短信随机码', type: 'string')]
    private string $codeId;

    #[OA\Property(property: 'status', description: '状态:1成功，2失败', type: 'integer')]
    private int $status;

    public function getCodeId(): string
    {
        return $this->codeId;
    }

    public function setCodeId(string $codeId): void
    {
        $this->codeId = $codeId;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
