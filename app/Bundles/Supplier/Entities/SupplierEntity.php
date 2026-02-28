<?php

declare(strict_types=1);

namespace App\Bundles\Supplier\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SupplierEntity')]
class SupplierEntity
{
    use DTOHelper;

    const string getSuppliersId = 'suppliers_id';

    const string getSuppliersName = 'suppliers_name'; // 供应商名称

    const string getSuppliersDesc = 'suppliers_desc'; // 供应商描述

    const string getIsCheck = 'is_check'; // 是否审核

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'suppliersId', description: '', type: 'integer')]
    private int $suppliersId;

    #[OA\Property(property: 'suppliersName', description: '供应商名称', type: 'string')]
    private string $suppliersName;

    #[OA\Property(property: 'suppliersDesc', description: '供应商描述', type: 'string')]
    private string $suppliersDesc;

    #[OA\Property(property: 'isCheck', description: '是否审核', type: 'integer')]
    private int $isCheck;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getSuppliersId(): int
    {
        return $this->suppliersId;
    }

    /**
     * 设置
     */
    public function setSuppliersId(int $suppliersId): void
    {
        $this->suppliersId = $suppliersId;
    }

    /**
     * 获取供应商名称
     */
    public function getSuppliersName(): string
    {
        return $this->suppliersName;
    }

    /**
     * 设置供应商名称
     */
    public function setSuppliersName(string $suppliersName): void
    {
        $this->suppliersName = $suppliersName;
    }

    /**
     * 获取供应商描述
     */
    public function getSuppliersDesc(): string
    {
        return $this->suppliersDesc;
    }

    /**
     * 设置供应商描述
     */
    public function setSuppliersDesc(string $suppliersDesc): void
    {
        $this->suppliersDesc = $suppliersDesc;
    }

    /**
     * 获取是否审核
     */
    public function getIsCheck(): int
    {
        return $this->isCheck;
    }

    /**
     * 设置是否审核
     */
    public function setIsCheck(int $isCheck): void
    {
        $this->isCheck = $isCheck;
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
