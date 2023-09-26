<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Focite\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdvertisingEntity')]
class AdvertisingEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'parent_id', description: '类型:0广告位,其他为广告内容', type: 'integer')]
    protected int $parentId;

    #[OA\Property(property: 'name', description: '标题', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'description', description: '描述', type: 'string')]
    protected string $description;

    #[OA\Property(property: 'width', description: '广告宽度', type: 'integer')]
    protected int $width;

    #[OA\Property(property: 'height', description: '广告高度', type: 'integer')]
    protected int $height;

    #[OA\Property(property: 'link', description: '链接地址', type: 'string')]
    protected string $link;

    #[OA\Property(property: 'code', description: '广告内容', type: 'string')]
    protected string $code;

    #[OA\Property(property: 'start_time', description: '开始时间', type: 'string')]
    protected string $startTime;

    #[OA\Property(property: 'end_time', description: '结束时间', type: 'string')]
    protected string $endTime;

    #[OA\Property(property: 'click_count', description: '点击量', type: 'integer')]
    protected int $clickCount;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

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
     * 获取类型:0广告位,其他为广告内容
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置类型:0广告位,其他为广告内容
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取标题
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置标题
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取广告宽度
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * 设置广告宽度
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * 获取广告高度
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * 设置广告高度
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
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
     * 获取广告内容
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置广告内容
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取开始时间
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * 设置开始时间
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取结束时间
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * 设置结束时间
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * 获取点击量
     */
    public function getClickCount(): int
    {
        return $this->clickCount;
    }

    /**
     * 设置点击量
     */
    public function setClickCount(int $clickCount): void
    {
        $this->clickCount = $clickCount;
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
