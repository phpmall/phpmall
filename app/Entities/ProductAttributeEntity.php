<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Generator\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductAttributeEntity')]
class ProductAttributeEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'product_type_id', description: '商品属性分类id', type: 'integer')]
    protected int $productTypeId;

    #[OA\Property(property: 'name', description: '名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'select_type', description: '属性选择类型：0->唯一；1->单选；2->多选；对应属性和参数意义不同；', type: 'integer')]
    protected int $selectType;

    #[OA\Property(property: 'input_type', description: '属性录入方式：0->手工录入；1->从列表中选取', type: 'integer')]
    protected int $inputType;

    #[OA\Property(property: 'input_list', description: '可选值列表，以逗号隔开', type: 'string')]
    protected string $inputList;

    #[OA\Property(property: 'sort', description: '排序字段：最高的可以单独上传图片', type: 'integer')]
    protected int $sort;

    #[OA\Property(property: 'filter_type', description: '分类筛选样式：1->普通；1->颜色', type: 'integer')]
    protected int $filterType;

    #[OA\Property(property: 'search_type', description: '检索类型；0->不需要进行检索；1->关键字检索；2->范围检索', type: 'integer')]
    protected int $searchType;

    #[OA\Property(property: 'related_status', description: '相同属性产品是否关联；0->不关联；1->关联', type: 'integer')]
    protected int $relatedStatus;

    #[OA\Property(property: 'hand_add_status', description: '是否支持手动新增；0->不支持；1->支持', type: 'integer')]
    protected int $handAddStatus;

    #[OA\Property(property: 'type', description: '属性的类型；0->规格；1->参数', type: 'integer')]
    protected int $type;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deletedAt;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取商品属性分类id
     */
    public function getProductTypeId(): int
    {
        return $this->productTypeId;
    }

    /**
     * 设置商品属性分类id
     */
    public function setProductTypeId(int $productTypeId): void
    {
        $this->productTypeId = $productTypeId;
    }

    /**
     * 获取名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取属性选择类型：0->唯一；1->单选；2->多选；对应属性和参数意义不同；
     */
    public function getSelectType(): int
    {
        return $this->selectType;
    }

    /**
     * 设置属性选择类型：0->唯一；1->单选；2->多选；对应属性和参数意义不同；
     */
    public function setSelectType(int $selectType): void
    {
        $this->selectType = $selectType;
    }

    /**
     * 获取属性录入方式：0->手工录入；1->从列表中选取
     */
    public function getInputType(): int
    {
        return $this->inputType;
    }

    /**
     * 设置属性录入方式：0->手工录入；1->从列表中选取
     */
    public function setInputType(int $inputType): void
    {
        $this->inputType = $inputType;
    }

    /**
     * 获取可选值列表，以逗号隔开
     */
    public function getInputList(): string
    {
        return $this->inputList;
    }

    /**
     * 设置可选值列表，以逗号隔开
     */
    public function setInputList(string $inputList): void
    {
        $this->inputList = $inputList;
    }

    /**
     * 获取排序字段：最高的可以单独上传图片
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序字段：最高的可以单独上传图片
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取分类筛选样式：1->普通；1->颜色
     */
    public function getFilterType(): int
    {
        return $this->filterType;
    }

    /**
     * 设置分类筛选样式：1->普通；1->颜色
     */
    public function setFilterType(int $filterType): void
    {
        $this->filterType = $filterType;
    }

    /**
     * 获取检索类型；0->不需要进行检索；1->关键字检索；2->范围检索
     */
    public function getSearchType(): int
    {
        return $this->searchType;
    }

    /**
     * 设置检索类型；0->不需要进行检索；1->关键字检索；2->范围检索
     */
    public function setSearchType(int $searchType): void
    {
        $this->searchType = $searchType;
    }

    /**
     * 获取相同属性产品是否关联；0->不关联；1->关联
     */
    public function getRelatedStatus(): int
    {
        return $this->relatedStatus;
    }

    /**
     * 设置相同属性产品是否关联；0->不关联；1->关联
     */
    public function setRelatedStatus(int $relatedStatus): void
    {
        $this->relatedStatus = $relatedStatus;
    }

    /**
     * 获取是否支持手动新增；0->不支持；1->支持
     */
    public function getHandAddStatus(): int
    {
        return $this->handAddStatus;
    }

    /**
     * 设置是否支持手动新增；0->不支持；1->支持
     */
    public function setHandAddStatus(int $handAddStatus): void
    {
        $this->handAddStatus = $handAddStatus;
    }

    /**
     * 获取属性的类型；0->规格；1->参数
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置属性的类型；0->规格；1->参数
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
