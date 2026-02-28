<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Responses\GoodsType;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsTypeResponse')]
class GoodsTypeResponse
{
    use DTOHelper;

    #[OA\Property(property: 'catId', description: '', type: 'integer')]
    private int $catId;

    #[OA\Property(property: 'catName', description: '分类名称', type: 'string')]
    private string $catName;

    #[OA\Property(property: 'enabled', description: '是否启用', type: 'integer')]
    private int $enabled;

    #[OA\Property(property: 'attrGroup', description: '属性分组', type: 'string')]
    private string $attrGroup;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getCatId(): int
    {
        return $this->catId;
    }

    /**
     * 设置
     */
    public function setCatId(int $catId): void
    {
        $this->catId = $catId;
    }

    /**
     * 获取分类名称
     */
    public function getCatName(): string
    {
        return $this->catName;
    }

    /**
     * 设置分类名称
     */
    public function setCatName(string $catName): void
    {
        $this->catName = $catName;
    }

    /**
     * 获取是否启用
     */
    public function getEnabled(): int
    {
        return $this->enabled;
    }

    /**
     * 设置是否启用
     */
    public function setEnabled(int $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * 获取属性分组
     */
    public function getAttrGroup(): string
    {
        return $this->attrGroup;
    }

    /**
     * 设置属性分组
     */
    public function setAttrGroup(string $attrGroup): void
    {
        $this->attrGroup = $attrGroup;
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
