<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Focite\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'NavigationEntity')]
class NavigationEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'parent_id', description: '父级ID', type: 'integer')]
    protected int $parentId;

    #[OA\Property(property: 'type', description: '导航类型', type: 'integer')]
    protected int $type;

    #[OA\Property(property: 'name', description: '导航文字', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'description', description: '导航描述', type: 'string')]
    protected string $description;

    #[OA\Property(property: 'icon', description: '小图标', type: 'string')]
    protected string $icon;

    #[OA\Property(property: 'link', description: '链接地址', type: 'string')]
    protected string $link;

    #[OA\Property(property: 'target', description: '打开方式', type: 'integer')]
    protected int $target;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

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
     * 获取导航类型
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置导航类型
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取导航文字
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置导航文字
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取导航描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置导航描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取小图标
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * 设置小图标
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * 获取链接地址
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * 设置链接地址
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * 获取打开方式
     */
    public function getTarget(): int
    {
        return $this->target;
    }

    /**
     * 设置打开方式
     */
    public function setTarget(int $target): void
    {
        $this->target = $target;
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
}
