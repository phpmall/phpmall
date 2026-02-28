<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsTypeAttributeEntity')]
class GoodsTypeAttributeEntity
{
    use DTOHelper;

    const string getAttrId = 'attr_id';

    const string getCatId = 'cat_id'; // 分类ID

    const string getAttrName = 'attr_name'; // 属性名称

    const string getAttrInputType = 'attr_input_type'; // 属性输入类型

    const string getAttrType = 'attr_type'; // 属性类型

    const string getAttrValues = 'attr_values'; // 属性值

    const string getAttrIndex = 'attr_index'; // 属性索引

    const string getSortOrder = 'sort_order'; // 排序顺序

    const string getIsLinked = 'is_linked'; // 是否关联

    const string getAttrGroup = 'attr_group'; // 属性分组

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'attrId', description: '', type: 'integer')]
    private int $attrId;

    #[OA\Property(property: 'catId', description: '分类ID', type: 'integer')]
    private int $catId;

    #[OA\Property(property: 'attrName', description: '属性名称', type: 'string')]
    private string $attrName;

    #[OA\Property(property: 'attrInputType', description: '属性输入类型', type: 'integer')]
    private int $attrInputType;

    #[OA\Property(property: 'attrType', description: '属性类型', type: 'integer')]
    private int $attrType;

    #[OA\Property(property: 'attrValues', description: '属性值', type: 'string')]
    private string $attrValues;

    #[OA\Property(property: 'attrIndex', description: '属性索引', type: 'integer')]
    private int $attrIndex;

    #[OA\Property(property: 'sortOrder', description: '排序顺序', type: 'integer')]
    private int $sortOrder;

    #[OA\Property(property: 'isLinked', description: '是否关联', type: 'integer')]
    private int $isLinked;

    #[OA\Property(property: 'attrGroup', description: '属性分组', type: 'integer')]
    private int $attrGroup;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getAttrId(): int
    {
        return $this->attrId;
    }

    /**
     * 设置
     */
    public function setAttrId(int $attrId): void
    {
        $this->attrId = $attrId;
    }

    /**
     * 获取分类ID
     */
    public function getCatId(): int
    {
        return $this->catId;
    }

    /**
     * 设置分类ID
     */
    public function setCatId(int $catId): void
    {
        $this->catId = $catId;
    }

    /**
     * 获取属性名称
     */
    public function getAttrName(): string
    {
        return $this->attrName;
    }

    /**
     * 设置属性名称
     */
    public function setAttrName(string $attrName): void
    {
        $this->attrName = $attrName;
    }

    /**
     * 获取属性输入类型
     */
    public function getAttrInputType(): int
    {
        return $this->attrInputType;
    }

    /**
     * 设置属性输入类型
     */
    public function setAttrInputType(int $attrInputType): void
    {
        $this->attrInputType = $attrInputType;
    }

    /**
     * 获取属性类型
     */
    public function getAttrType(): int
    {
        return $this->attrType;
    }

    /**
     * 设置属性类型
     */
    public function setAttrType(int $attrType): void
    {
        $this->attrType = $attrType;
    }

    /**
     * 获取属性值
     */
    public function getAttrValues(): string
    {
        return $this->attrValues;
    }

    /**
     * 设置属性值
     */
    public function setAttrValues(string $attrValues): void
    {
        $this->attrValues = $attrValues;
    }

    /**
     * 获取属性索引
     */
    public function getAttrIndex(): int
    {
        return $this->attrIndex;
    }

    /**
     * 设置属性索引
     */
    public function setAttrIndex(int $attrIndex): void
    {
        $this->attrIndex = $attrIndex;
    }

    /**
     * 获取排序顺序
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * 设置排序顺序
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * 获取是否关联
     */
    public function getIsLinked(): int
    {
        return $this->isLinked;
    }

    /**
     * 设置是否关联
     */
    public function setIsLinked(int $isLinked): void
    {
        $this->isLinked = $isLinked;
    }

    /**
     * 获取属性分组
     */
    public function getAttrGroup(): int
    {
        return $this->attrGroup;
    }

    /**
     * 设置属性分组
     */
    public function setAttrGroup(int $attrGroup): void
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
