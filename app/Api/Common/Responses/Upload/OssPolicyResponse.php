<?php

declare(strict_types=1);

namespace App\Api\Common\Responses\Upload;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommonUploadOssPolicyResponse')]
class OssPolicyResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'access_key_id', description: 'AccessKey ID', type: 'string')]
    private string $accessKeyId;

    #[OA\Property(property: 'policy', description: '上传策略', type: 'string')]
    private string $policy;

    #[OA\Property(property: 'signature', description: '签名', type: 'string')]
    private string $signature;

    #[OA\Property(property: 'host', description: '上传域名', type: 'string')]
    private string $host;

    #[OA\Property(property: 'expire', description: '过期时间戳', type: 'integer')]
    private int $expire;

    #[OA\Property(property: 'callback', description: '回调地址', type: 'string', nullable: true)]
    private ?string $callback;

    #[OA\Property(property: 'dir', description: '上传目录前缀', type: 'string')]
    private string $dir;

    public function getAccessKeyId(): string
    {
        return $this->accessKeyId;
    }

    public function setAccessKeyId(string $accessKeyId): void
    {
        $this->accessKeyId = $accessKeyId;
    }

    public function getPolicy(): string
    {
        return $this->policy;
    }

    public function setPolicy(string $policy): void
    {
        $this->policy = $policy;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function setSignature(string $signature): void
    {
        $this->signature = $signature;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function getExpire(): int
    {
        return $this->expire;
    }

    public function setExpire(int $expire): void
    {
        $this->expire = $expire;
    }

    public function getCallback(): ?string
    {
        return $this->callback;
    }

    public function setCallback(?string $callback): void
    {
        $this->callback = $callback;
    }

    public function getDir(): string
    {
        return $this->dir;
    }

    public function setDir(string $dir): void
    {
        $this->dir = $dir;
    }
}
