<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsCatRecommendEntity')]
class GoodsCatRecommendEntity
{
    use DTOHelper;

    const string getId = 'id'; // ID

    const string getCatId = 'cat_id'; // 分类ID

    const string getRecommendType = 'recommend_type'; // 推荐类型

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'catId', description: '分类ID', type: 'integer')]
    private int $catId;

    #[OA\Property(property: 'recommendType', description: '推荐类型', type: 'integer')]
    private int $recommendType;

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
     * 获取分类ID
     */
    public function getCatId(): int
    {
        return $this->catId;
    }

    /**
     * 设置分类ID
     */
    public function setCatId(int $catId): void
    {
        $this->catId = $catId;
    }

    /**
     * 获取推荐类型
     */
    public function getRecommendType(): int
    {
        return $this->recommendType;
    }

    /**
     * 设置推荐类型
     */
    public function setRecommendType(int $recommendType): void
    {
        $this->recommendType = $recommendType;
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
