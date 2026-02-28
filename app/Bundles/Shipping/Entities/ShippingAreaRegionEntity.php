<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShippingAreaRegionEntity')]
class ShippingAreaRegionEntity
{
    use DTOHelper;

    const string getId = 'id'; // ID

    const string getShippingAreaId = 'shipping_area_id'; // 配送区域ID

    const string getRegionId = 'region_id'; // 地区ID

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shippingAreaId', description: '配送区域ID', type: 'integer')]
    private int $shippingAreaId;

    #[OA\Property(property: 'regionId', description: '地区ID', type: 'integer')]
    private int $regionId;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取配送区域ID
     */
    public function getShippingAreaId(): int
    {
        return $this->shippingAreaId;
    }

    /**
     * 设置配送区域ID
     */
    public function setShippingAreaId(int $shippingAreaId): void
    {
        $this->shippingAreaId = $shippingAreaId;
    }

    /**
     * 获取地区ID
     */
    public function getRegionId(): int
    {
        return $this->regionId;
    }

    /**
     * 设置地区ID
     */
    public function setRegionId(int $regionId): void
    {
        $this->regionId = $regionId;
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
