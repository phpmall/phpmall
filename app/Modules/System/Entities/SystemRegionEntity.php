<?php

declare(strict_types=1);

namespace App\Modules\System\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemRegionEntity')]
class SystemRegionEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getParentCode = 'parent_code'; // 父级地区编码

    public const string getName = 'name'; // 地区名称

    public const string getCode = 'code'; // 地区编码

    public const string getLevel = 'level'; // 地区层级:1省,2市,3区

    public const string getZipCode = 'zip_code'; // 邮编

    public const string getHasChildren = 'has_children'; // 是否有子级

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentCode', description: '父级地区编码', type: 'string')]
    private string $parentCode;

    #[OA\Property(property: 'name', description: '地区名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'code', description: '地区编码', type: 'string')]
    private string $code;

    #[OA\Property(property: 'level', description: '地区层级:1省,2市,3区', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'zipCode', description: '邮编', type: 'string')]
    private string $zipCode;

    #[OA\Property(property: 'hasChildren', description: '是否有子级', type: 'integer')]
    private int $hasChildren;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

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
     * 获取父级地区编码
     */
    public function getParentCode(): string
    {
        return $this->parentCode;
    }

    /**
     * 设置父级地区编码
     */
    public function setParentCode(string $parentCode): void
    {
        $this->parentCode = $parentCode;
    }

    /**
     * 获取地区名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置地区名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取地区编码
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置地区编码
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取地区层级:1省,2市,3区
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * 设置地区层级:1省,2市,3区
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * 获取邮编
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * 设置邮编
     */
    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * 获取是否有子级
     */
    public function getHasChildren(): int
    {
        return $this->hasChildren;
    }

    /**
     * 设置是否有子级
     */
    public function setHasChildren(int $hasChildren): void
    {
        $this->hasChildren = $hasChildren;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
