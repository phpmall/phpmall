<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\ShopDecoration;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerShopDecorationPreviewResponse')]
class ShopDecorationPreviewResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'preview_url', description: '预览链接', type: 'string')]
    private string $previewUrl;

    #[OA\Property(property: 'expire_at', description: '预览链接过期时间', type: 'string', format: 'date-time')]
    private string $expireAt;

    public function getPreviewUrl(): string
    {
        return $this->previewUrl;
    }

    public function setPreviewUrl(string $previewUrl): void
    {
        $this->previewUrl = $previewUrl;
    }

    public function getExpireAt(): string
    {
        return $this->expireAt;
    }

    public function setExpireAt(string $expireAt): void
    {
        $this->expireAt = $expireAt;
    }
}
