<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopConfigEntity')]
class ShopConfigEntity
{
    use DTOHelper;

    const string getId = 'id'; // ID

    const string getParentId = 'parent_id'; // 父级ID

    const string getCode = 'code'; // 配置编码

    const string getType = 'type'; // 配置类型

    const string getStoreRange = 'store_range'; // 存储范围

    const string getStoreDir = 'store_dir'; // 存储目录

    const string getValue = 'value'; // 配置值

    const string getSortOrder = 'sort_order'; // 排序顺序

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'code', description: '配置编码', type: 'string')]
    private string $code;

    #[OA\Property(property: 'type', description: '配置类型', type: 'string')]
    private string $type;

    #[OA\Property(property: 'storeRange', description: '存储范围', type: 'string')]
    private string $storeRange;

    #[OA\Property(property: 'storeDir', description: '存储目录', type: 'string')]
    private string $storeDir;

    #[OA\Property(property: 'value', description: '配置值', type: 'string')]
    private string $value;

    #[OA\Property(property: 'sortOrder', description: '排序顺序', type: 'integer')]
    private int $sortOrder;

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
     * 获取配置编码
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置配置编码
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取配置类型
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置配置类型
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取存储范围
     */
    public function getStoreRange(): string
    {
        return $this->storeRange;
    }

    /**
     * 设置存储范围
     */
    public function setStoreRange(string $storeRange): void
    {
        $this->storeRange = $storeRange;
    }

    /**
     * 获取存储目录
     */
    public function getStoreDir(): string
    {
        return $this->storeDir;
    }

    /**
     * 设置存储目录
     */
    public function setStoreDir(string $storeDir): void
    {
        $this->storeDir = $storeDir;
    }

    /**
     * 获取配置值
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * 设置配置值
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
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
