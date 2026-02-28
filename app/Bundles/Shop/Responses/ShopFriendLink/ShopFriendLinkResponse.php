<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Responses\ShopFriendLink;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopFriendLinkResponse')]
class ShopFriendLinkResponse
{
    use DTOHelper;

    #[OA\Property(property: 'linkId', description: '', type: 'integer')]
    private int $linkId;

    #[OA\Property(property: 'linkName', description: '链接名称', type: 'string')]
    private string $linkName;

    #[OA\Property(property: 'linkUrl', description: '链接地址', type: 'string')]
    private string $linkUrl;

    #[OA\Property(property: 'linkLogo', description: '链接Logo', type: 'string')]
    private string $linkLogo;

    #[OA\Property(property: 'showOrder', description: '排序', type: 'integer')]
    private int $showOrder;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getLinkId(): int
    {
        return $this->linkId;
    }

    /**
     * 设置
     */
    public function setLinkId(int $linkId): void
    {
        $this->linkId = $linkId;
    }

    /**
     * 获取链接名称
     */
    public function getLinkName(): string
    {
        return $this->linkName;
    }

    /**
     * 设置链接名称
     */
    public function setLinkName(string $linkName): void
    {
        $this->linkName = $linkName;
    }

    /**
     * 获取链接地址
     */
    public function getLinkUrl(): string
    {
        return $this->linkUrl;
    }

    /**
     * 设置链接地址
     */
    public function setLinkUrl(string $linkUrl): void
    {
        $this->linkUrl = $linkUrl;
    }

    /**
     * 获取链接Logo
     */
    public function getLinkLogo(): string
    {
        return $this->linkLogo;
    }

    /**
     * 设置链接Logo
     */
    public function setLinkLogo(string $linkLogo): void
    {
        $this->linkLogo = $linkLogo;
    }

    /**
     * 获取排序
     */
    public function getShowOrder(): int
    {
        return $this->showOrder;
    }

    /**
     * 设置排序
     */
    public function setShowOrder(int $showOrder): void
    {
        $this->showOrder = $showOrder;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedTime(): string
    {
        return $this->createdTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedTime(string $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedTime(): string
    {
        return $this->updatedTime;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedTime(string $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}
