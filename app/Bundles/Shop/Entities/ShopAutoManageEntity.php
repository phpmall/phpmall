<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopAutoManageEntity')]
class ShopAutoManageEntity
{
    use DTOHelper;

    const string getId = 'id'; // ID

    const string getItemId = 'item_id'; // 项目ID

    const string getType = 'type'; // 类型

    const string getStarttime = 'starttime'; // 开始时间

    const string getEndtime = 'endtime'; // 结束时间

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'itemId', description: '项目ID', type: 'integer')]
    private int $itemId;

    #[OA\Property(property: 'type', description: '类型', type: 'string')]
    private string $type;

    #[OA\Property(property: 'starttime', description: '开始时间', type: 'integer')]
    private int $starttime;

    #[OA\Property(property: 'endtime', description: '结束时间', type: 'integer')]
    private int $endtime;

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
     * 获取项目ID
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * 设置项目ID
     */
    public function setItemId(int $itemId): void
    {
        $this->itemId = $itemId;
    }

    /**
     * 获取类型
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取开始时间
     */
    public function getStarttime(): int
    {
        return $this->starttime;
    }

    /**
     * 设置开始时间
     */
    public function setStarttime(int $starttime): void
    {
        $this->starttime = $starttime;
    }

    /**
     * 获取结束时间
     */
    public function getEndtime(): int
    {
        return $this->endtime;
    }

    /**
     * 设置结束时间
     */
    public function setEndtime(int $endtime): void
    {
        $this->endtime = $endtime;
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
