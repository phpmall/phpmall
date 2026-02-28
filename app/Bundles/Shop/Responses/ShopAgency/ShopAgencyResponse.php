<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Responses\ShopAgency;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopAgencyResponse')]
class ShopAgencyResponse
{
    use DTOHelper;

    #[OA\Property(property: 'agencyId', description: '', type: 'integer')]
    private int $agencyId;

    #[OA\Property(property: 'agencyName', description: '办事处名称', type: 'string')]
    private string $agencyName;

    #[OA\Property(property: 'agencyDesc', description: '办事处描述', type: 'string')]
    private string $agencyDesc;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getAgencyId(): int
    {
        return $this->agencyId;
    }

    /**
     * 设置
     */
    public function setAgencyId(int $agencyId): void
    {
        $this->agencyId = $agencyId;
    }

    /**
     * 获取办事处名称
     */
    public function getAgencyName(): string
    {
        return $this->agencyName;
    }

    /**
     * 设置办事处名称
     */
    public function setAgencyName(string $agencyName): void
    {
        $this->agencyName = $agencyName;
    }

    /**
     * 获取办事处描述
     */
    public function getAgencyDesc(): string
    {
        return $this->agencyDesc;
    }

    /**
     * 设置办事处描述
     */
    public function setAgencyDesc(string $agencyDesc): void
    {
        $this->agencyDesc = $agencyDesc;
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
