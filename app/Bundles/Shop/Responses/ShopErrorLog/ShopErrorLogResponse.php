<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Responses\ShopErrorLog;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopErrorLogResponse')]
class ShopErrorLogResponse
{
    use DTOHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'info', description: '错误信息', type: 'string')]
    private string $info;

    #[OA\Property(property: 'file', description: '错误文件', type: 'string')]
    private string $file;

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
     * 获取错误信息
     */
    public function getInfo(): string
    {
        return $this->info;
    }

    /**
     * 设置错误信息
     */
    public function setInfo(string $info): void
    {
        $this->info = $info;
    }

    /**
     * 获取错误文件
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * 设置错误文件
     */
    public function setFile(string $file): void
    {
        $this->file = $file;
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
