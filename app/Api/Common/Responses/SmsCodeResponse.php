<?php

declare(strict_types=1);

namespace App\Api\Common\Responses;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SmsCodeResponse')]
class SmsCodeResponse
{
    use DTOHelper;

    #[OA\Property(property: 'codeId', description: '短信随机码', type: 'string')]
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
