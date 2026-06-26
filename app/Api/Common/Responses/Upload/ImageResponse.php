<?php

declare(strict_types=1);

namespace App\Api\Common\Responses\Upload;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommonUploadImageResponse')]
class ImageResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'url', description: '图片访问URL', type: 'string')]
    private string $url;

    #[OA\Property(property: 'path', description: '图片存储路径', type: 'string')]
    private string $path;

    #[OA\Property(property: 'name', description: '原始文件名', type: 'string')]
    private string $name;

    #[OA\Property(property: 'size', description: '文件大小(字节)', type: 'integer')]
    private int $size;

    #[OA\Property(property: 'mime_type', description: 'MIME类型', type: 'string')]
    private string $mimeType;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }
}
