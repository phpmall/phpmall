<?php

declare(strict_types=1);

namespace App\Bundles\Template\Responses\Template;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'TemplateResponse')]
class TemplateResponse
{
    use DTOHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'filename', description: '文件名', type: 'string')]
    private string $filename;

    #[OA\Property(property: 'region', description: '区域', type: 'string')]
    private string $region;

    #[OA\Property(property: 'library', description: '库', type: 'string')]
    private string $library;

    #[OA\Property(property: 'sortOrder', description: '排序顺序', type: 'integer')]
    private int $sortOrder;

    #[OA\Property(property: 'idValue', description: '关联ID', type: 'integer')]
    private int $idValue;

    #[OA\Property(property: 'number', description: '数量', type: 'integer')]
    private int $number;

    #[OA\Property(property: 'type', description: '类型', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'theme', description: '主题', type: 'string')]
    private string $theme;

    #[OA\Property(property: 'remarks', description: '备注', type: 'string')]
    private string $remarks;

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
     * 获取文件名
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * 设置文件名
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * 获取区域
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * 设置区域
     */
    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    /**
     * 获取库
     */
    public function getLibrary(): string
    {
        return $this->library;
    }

    /**
     * 设置库
     */
    public function setLibrary(string $library): void
    {
        $this->library = $library;
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
     * 获取关联ID
     */
    public function getIdValue(): int
    {
        return $this->idValue;
    }

    /**
     * 设置关联ID
     */
    public function setIdValue(int $idValue): void
    {
        $this->idValue = $idValue;
    }

    /**
     * 获取数量
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * 设置数量
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * 获取类型
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置类型
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取主题
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * 设置主题
     */
    public function setTheme(string $theme): void
    {
        $this->theme = $theme;
    }

    /**
     * 获取备注
     */
    public function getRemarks(): string
    {
        return $this->remarks;
    }

    /**
     * 设置备注
     */
    public function setRemarks(string $remarks): void
    {
        $this->remarks = $remarks;
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
