<?php

declare(strict_types=1);

namespace App\Bundles\Article\Responses\ArticleCat;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ArticleCatResponse')]
class ArticleCatResponse
{
    use DTOHelper;

    #[OA\Property(property: 'catId', description: '', type: 'integer')]
    private int $catId;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'catName', description: '分类名称', type: 'string')]
    private string $catName;

    #[OA\Property(property: 'catType', description: '分类类型', type: 'integer')]
    private int $catType;

    #[OA\Property(property: 'keywords', description: '关键词', type: 'string')]
    private string $keywords;

    #[OA\Property(property: 'catDesc', description: '分类描述', type: 'string')]
    private string $catDesc;

    #[OA\Property(property: 'sortOrder', description: '排序', type: 'integer')]
    private int $sortOrder;

    #[OA\Property(property: 'showInNav', description: '是否在导航显示', type: 'integer')]
    private int $showInNav;

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
     * 获取分类类型
     */
    public function getCatType(): int
    {
        return $this->catType;
    }

    /**
     * 设置分类类型
     */
    public function setCatType(int $catType): void
    {
        $this->catType = $catType;
    }

    /**
     * 获取关键词
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * 设置关键词
     */
    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * 获取分类描述
     */
    public function getCatDesc(): string
    {
        return $this->catDesc;
    }

    /**
     * 设置分类描述
     */
    public function setCatDesc(string $catDesc): void
    {
        $this->catDesc = $catDesc;
    }

    /**
     * 获取排序
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * 设置排序
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * 获取是否在导航显示
     */
    public function getShowInNav(): int
    {
        return $this->showInNav;
    }

    /**
     * 设置是否在导航显示
     */
    public function setShowInNav(int $showInNav): void
    {
        $this->showInNav = $showInNav;
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
