<?php

declare(strict_types=1);

namespace App\Bundles\Material\Responses;

use Juling\Generator\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UploadResponse')]
class UploadResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'url', description: '素材URL地址', type: 'string')]
    private string $url;

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}
