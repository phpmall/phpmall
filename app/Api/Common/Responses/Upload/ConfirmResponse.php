<?php

declare(strict_types=1);

namespace App\Api\Common\Responses\Upload;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommonUploadConfirmResponse')]
class ConfirmResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'url', description: '文件访问URL', type: 'string')]
    private string $url;

    #[OA\Property(property: 'path', description: '文件存储路径', type: 'string')]
    private string $path;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
