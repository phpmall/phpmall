<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Responses\ShippingArea;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShippingAreaResponse')]
class ShippingAreaResponse
{
    use DTOHelper;

    #[OA\Property(property: 'shippingAreaId', description: '', type: 'integer')]
    private int $shippingAreaId;

    #[OA\Property(property: 'shippingAreaName', description: '配送区域名称', type: 'string')]
    private string $shippingAreaName;

    #[OA\Property(property: 'shippingId', description: '配送方式ID', type: 'integer')]
    private int $shippingId;

    #[OA\Property(property: 'configure', description: '配置信息', type: 'string')]
    private string $configure;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getShippingAreaId(): int
    {
        return $this->shippingAreaId;
    }

    /**
     * 设置
     */
    public function setShippingAreaId(int $shippingAreaId): void
    {
        $this->shippingAreaId = $shippingAreaId;
    }

    /**
     * 获取配送区域名称
     */
    public function getShippingAreaName(): string
    {
        return $this->shippingAreaName;
    }

    /**
     * 设置配送区域名称
     */
    public function setShippingAreaName(string $shippingAreaName): void
    {
        $this->shippingAreaName = $shippingAreaName;
    }

    /**
     * 获取配送方式ID
     */
    public function getShippingId(): int
    {
        return $this->shippingId;
    }

    /**
     * 设置配送方式ID
     */
    public function setShippingId(int $shippingId): void
    {
        $this->shippingId = $shippingId;
    }

    /**
     * 获取配置信息
     */
    public function getConfigure(): string
    {
        return $this->configure;
    }

    /**
     * 设置配置信息
     */
    public function setConfigure(string $configure): void
    {
        $this->configure = $configure;
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
