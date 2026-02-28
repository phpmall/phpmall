<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Responses\ShopPlugins;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopPluginsResponse')]
class ShopPluginsResponse
{
    use DTOHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'code', description: '插件编码', type: 'string')]
    private string $code;

    #[OA\Property(property: 'version', description: '版本号', type: 'string')]
    private string $version;

    #[OA\Property(property: 'library', description: '库名', type: 'string')]
    private string $library;

    #[OA\Property(property: 'assign', description: '分配状态', type: 'integer')]
    private int $assign;

    #[OA\Property(property: 'installDate', description: '安装日期', type: 'integer')]
    private int $installDate;

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
     * 获取插件编码
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置插件编码
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取版本号
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * 设置版本号
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * 获取库名
     */
    public function getLibrary(): string
    {
        return $this->library;
    }

    /**
     * 设置库名
     */
    public function setLibrary(string $library): void
    {
        $this->library = $library;
    }

    /**
     * 获取分配状态
     */
    public function getAssign(): int
    {
        return $this->assign;
    }

    /**
     * 设置分配状态
     */
    public function setAssign(int $assign): void
    {
        $this->assign = $assign;
    }

    /**
     * 获取安装日期
     */
    public function getInstallDate(): int
    {
        return $this->installDate;
    }

    /**
     * 设置安装日期
     */
    public function setInstallDate(int $installDate): void
    {
        $this->installDate = $installDate;
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
