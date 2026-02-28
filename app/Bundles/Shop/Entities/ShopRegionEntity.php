<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopRegionEntity')]
class ShopRegionEntity
{
    use DTOHelper;

    const string getRegionId = 'region_id';

    const string getRegionType = 'region_type'; // 地区类型

    const string getAgencyId = 'agency_id'; // 办事处ID

    const string getParentId = 'parent_id'; // 父级ID

    const string getRegionName = 'region_name'; // 地区名称

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'regionId', description: '', type: 'integer')]
    private int $regionId;

    #[OA\Property(property: 'regionType', description: '地区类型', type: 'integer')]
    private int $regionType;

    #[OA\Property(property: 'agencyId', description: '办事处ID', type: 'integer')]
    private int $agencyId;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'regionName', description: '地区名称', type: 'string')]
    private string $regionName;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getRegionId(): int
    {
        return $this->regionId;
    }

    /**
     * 设置
     */
    public function setRegionId(int $regionId): void
    {
        $this->regionId = $regionId;
    }

    /**
     * 获取地区类型
     */
    public function getRegionType(): int
    {
        return $this->regionType;
    }

    /**
     * 设置地区类型
     */
    public function setRegionType(int $regionType): void
    {
        $this->regionType = $regionType;
    }

    /**
     * 获取办事处ID
     */
    public function getAgencyId(): int
    {
        return $this->agencyId;
    }

    /**
     * 设置办事处ID
     */
    public function setAgencyId(int $agencyId): void
    {
        $this->agencyId = $agencyId;
    }

    /**
     * 获取父级ID
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取地区名称
     */
    public function getRegionName(): string
    {
        return $this->regionName;
    }

    /**
     * 设置地区名称
     */
    public function setRegionName(string $regionName): void
    {
        $this->regionName = $regionName;
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
