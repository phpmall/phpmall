<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsCategoryEntity')]
class GoodsCategoryEntity
{
    use DTOHelper;

    const string getCatId = 'cat_id';

    const string getCatName = 'cat_name'; // 分类名称

    const string getKeywords = 'keywords'; // 关键词

    const string getCatDesc = 'cat_desc'; // 分类描述

    const string getParentId = 'parent_id'; // 父级ID

    const string getSortOrder = 'sort_order'; // 排序

    const string getTemplateFile = 'template_file'; // 模板文件

    const string getMeasureUnit = 'measure_unit'; // 计量单位

    const string getShowInNav = 'show_in_nav'; // 是否在导航显示

    const string getStyle = 'style'; // 样式

    const string getIsShow = 'is_show'; // 是否显示

    const string getGrade = 'grade'; // 等级

    const string getFilterAttr = 'filter_attr'; // 筛选属性

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'catId', description: '', type: 'integer')]
    private int $catId;

    #[OA\Property(property: 'catName', description: '分类名称', type: 'string')]
    private string $catName;

    #[OA\Property(property: 'keywords', description: '关键词', type: 'string')]
    private string $keywords;

    #[OA\Property(property: 'catDesc', description: '分类描述', type: 'string')]
    private string $catDesc;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'sortOrder', description: '排序', type: 'integer')]
    private int $sortOrder;

    #[OA\Property(property: 'templateFile', description: '模板文件', type: 'string')]
    private string $templateFile;

    #[OA\Property(property: 'measureUnit', description: '计量单位', type: 'string')]
    private string $measureUnit;

    #[OA\Property(property: 'showInNav', description: '是否在导航显示', type: 'integer')]
    private int $showInNav;

    #[OA\Property(property: 'style', description: '样式', type: 'string')]
    private string $style;

    #[OA\Property(property: 'isShow', description: '是否显示', type: 'integer')]
    private int $isShow;

    #[OA\Property(property: 'grade', description: '等级', type: 'integer')]
    private int $grade;

    #[OA\Property(property: 'filterAttr', description: '筛选属性', type: 'string')]
    private string $filterAttr;

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
     * 获取模板文件
     */
    public function getTemplateFile(): string
    {
        return $this->templateFile;
    }

    /**
     * 设置模板文件
     */
    public function setTemplateFile(string $templateFile): void
    {
        $this->templateFile = $templateFile;
    }

    /**
     * 获取计量单位
     */
    public function getMeasureUnit(): string
    {
        return $this->measureUnit;
    }

    /**
     * 设置计量单位
     */
    public function setMeasureUnit(string $measureUnit): void
    {
        $this->measureUnit = $measureUnit;
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
     * 获取样式
     */
    public function getStyle(): string
    {
        return $this->style;
    }

    /**
     * 设置样式
     */
    public function setStyle(string $style): void
    {
        $this->style = $style;
    }

    /**
     * 获取是否显示
     */
    public function getIsShow(): int
    {
        return $this->isShow;
    }

    /**
     * 设置是否显示
     */
    public function setIsShow(int $isShow): void
    {
        $this->isShow = $isShow;
    }

    /**
     * 获取等级
     */
    public function getGrade(): int
    {
        return $this->grade;
    }

    /**
     * 设置等级
     */
    public function setGrade(int $grade): void
    {
        $this->grade = $grade;
    }

    /**
     * 获取筛选属性
     */
    public function getFilterAttr(): string
    {
        return $this->filterAttr;
    }

    /**
     * 设置筛选属性
     */
    public function setFilterAttr(string $filterAttr): void
    {
        $this->filterAttr = $filterAttr;
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
