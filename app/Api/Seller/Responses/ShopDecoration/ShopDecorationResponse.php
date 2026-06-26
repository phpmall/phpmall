<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\ShopDecoration;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerShopDecorationResponse')]
class ShopDecorationResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '装修ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shop_id', description: '店铺ID', type: 'integer')]
    private int $shopId;

    #[OA\Property(property: 'theme', description: '主题风格', type: 'string')]
    private string $theme;

    #[OA\Property(property: 'color_scheme', description: '配色方案', type: 'string', nullable: true)]
    private ?string $colorScheme;

    #[OA\Property(property: 'banner_images', description: '轮播图列表', type: 'array', items: new OA\Items(type: 'string'))]
    private array $bannerImages;

    #[OA\Property(property: 'nav_config', description: '导航配置(JSON)', type: 'string', nullable: true)]
    private ?string $navConfig;

    #[OA\Property(property: 'custom_modules', description: '自定义模块(JSON)', type: 'string', nullable: true)]
    private ?string $customModules;

    #[OA\Property(property: 'is_enabled', description: '是否启用:0否,1是', type: 'integer')]
    private int $isEnabled;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string', format: 'date-time')]
    private string $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getShopId(): int
    {
        return $this->shopId;
    }

    public function setShopId(int $shopId): void
    {
        $this->shopId = $shopId;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): void
    {
        $this->theme = $theme;
    }

    public function getColorScheme(): ?string
    {
        return $this->colorScheme;
    }

    public function setColorScheme(?string $colorScheme): void
    {
        $this->colorScheme = $colorScheme;
    }

    public function getBannerImages(): array
    {
        return $this->bannerImages;
    }

    public function setBannerImages(array $bannerImages): void
    {
        $this->bannerImages = $bannerImages;
    }

    public function getNavConfig(): ?string
    {
        return $this->navConfig;
    }

    public function setNavConfig(?string $navConfig): void
    {
        $this->navConfig = $navConfig;
    }

    public function getCustomModules(): ?string
    {
        return $this->customModules;
    }

    public function setCustomModules(?string $customModules): void
    {
        $this->customModules = $customModules;
    }

    public function getIsEnabled(): int
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(int $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
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
