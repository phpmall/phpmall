<?php

declare(strict_types=1);

namespace App\Modules\Product\API\Manager\Responses\Category;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CategoryResponse')]
class CategoryResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'name', description: '名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'pinyin', description: '拼音', type: 'string')]
    private string $pinyin;

    #[OA\Property(property: 'firstLetter', description: '首字母', type: 'string')]
    private string $firstLetter;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'createdAt', description: '', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '', type: 'string')]
    private string $deletedAt;

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
     * 获取拼音
     */
    public function getPinyin(): string
    {
        return $this->pinyin;
    }

    /**
     * 设置拼音
     */
    public function setPinyin(string $pinyin): void
    {
        $this->pinyin = $pinyin;
    }

    /**
     * 获取首字母
     */
    public function getFirstLetter(): string
    {
        return $this->firstLetter;
    }

    /**
     * 设置首字母
     */
    public function setFirstLetter(string $firstLetter): void
    {
        $this->firstLetter = $firstLetter;
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

    /**
     * 获取状态:1正常,2禁用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1正常,2禁用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
