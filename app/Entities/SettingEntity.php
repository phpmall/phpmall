<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Generator\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SettingEntity')]
class SettingEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'parent_id', description: '父节点id', type: 'integer')]
    protected int $parentId;

    #[OA\Property(property: 'name', description: '配置名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'code', description: '配置code', type: 'string')]
    protected string $code;

    #[OA\Property(property: 'type', description: '配置类型：text、select、file、hidden等', type: 'string')]
    protected string $type;

    #[OA\Property(property: 'range', description: '配置数组索引', type: 'string')]
    protected string $range;

    #[OA\Property(property: 'value', description: '该项配置的值', type: 'string')]
    protected string $value;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

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
     * 获取父节点id
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父节点id
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取配置名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置配置名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取配置code
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置配置code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取配置类型：text、select、file、hidden等
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置配置类型：text、select、file、hidden等
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取配置数组索引
     */
    public function getRange(): string
    {
        return $this->range;
    }

    /**
     * 设置配置数组索引
     */
    public function setRange(string $range): void
    {
        $this->range = $range;
    }

    /**
     * 获取该项配置的值
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * 设置该项配置的值
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }
}
