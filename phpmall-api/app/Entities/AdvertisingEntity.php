<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdvertisingEntity')]
class AdvertisingEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'parent_id', description: '类型:0广告位,其他为广告内容', type: 'integer')]
    protected int $parent_id;

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
    protected string $start_time;

    #[OA\Property(property: 'end_time', description: '结束时间', type: 'string')]
    protected string $end_time;

    #[OA\Property(property: 'click_count', description: '点击量', type: 'integer')]
    protected int $click_count;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deleted_at;

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
        return $this->parent_id;
    }

    /**
     * 设置类型:0广告位,其他为广告内容
     */
    public function setParentId(int $parent_id): void
    {
        $this->parent_id = $parent_id;
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
        return $this->start_time;
    }

    /**
     * 设置开始时间
     */
    public function setStartTime(string $start_time): void
    {
        $this->start_time = $start_time;
    }

    /**
     * 获取结束时间
     */
    public function getEndTime(): string
    {
        return $this->end_time;
    }

    /**
     * 设置结束时间
     */
    public function setEndTime(string $end_time): void
    {
        $this->end_time = $end_time;
    }

    /**
     * 获取点击量
     */
    public function getClickCount(): int
    {
        return $this->click_count;
    }

    /**
     * 设置点击量
     */
    public function setClickCount(int $click_count): void
    {
        $this->click_count = $click_count;
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
        return $this->created_at;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deleted_at;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }
}
