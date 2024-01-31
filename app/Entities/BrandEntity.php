<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Generator\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'BrandEntity')]
class BrandEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'name', description: '品牌的名称或商标', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'first_letter', description: '品牌名称的首字母', type: 'string')]
    protected string $firstLetter;

    #[OA\Property(property: 'logo', description: '品牌的标识性Logo图片地址', type: 'string')]
    protected string $logo;

    #[OA\Property(property: 'big_pic', description: '专区大图', type: 'string')]
    protected string $bigPic;

    #[OA\Property(property: 'brand_story', description: '品牌故事', type: 'string')]
    protected string $brandStory;

    #[OA\Property(property: 'factory_status', description: '是否为品牌制造商：0->不是；1->是', type: 'integer')]
    protected int $factoryStatus;

    #[OA\Property(property: 'show_status', description: '是否显示', type: 'integer')]
    protected int $showStatus;

    #[OA\Property(property: 'product_count', description: '产品数量', type: 'integer')]
    protected int $productCount;

    #[OA\Property(property: 'product_comment_count', description: '产品评论数量', type: 'integer')]
    protected int $productCommentCount;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

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
     * 获取品牌的名称或商标
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置品牌的名称或商标
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取品牌名称的首字母
     */
    public function getFirstLetter(): string
    {
        return $this->firstLetter;
    }

    /**
     * 设置品牌名称的首字母
     */
    public function setFirstLetter(string $firstLetter): void
    {
        $this->firstLetter = $firstLetter;
    }

    /**
     * 获取品牌的标识性Logo图片地址
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * 设置品牌的标识性Logo图片地址
     */
    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * 获取专区大图
     */
    public function getBigPic(): string
    {
        return $this->bigPic;
    }

    /**
     * 设置专区大图
     */
    public function setBigPic(string $bigPic): void
    {
        $this->bigPic = $bigPic;
    }

    /**
     * 获取品牌故事
     */
    public function getBrandStory(): string
    {
        return $this->brandStory;
    }

    /**
     * 设置品牌故事
     */
    public function setBrandStory(string $brandStory): void
    {
        $this->brandStory = $brandStory;
    }

    /**
     * 获取是否为品牌制造商：0->不是；1->是
     */
    public function getFactoryStatus(): int
    {
        return $this->factoryStatus;
    }

    /**
     * 设置是否为品牌制造商：0->不是；1->是
     */
    public function setFactoryStatus(int $factoryStatus): void
    {
        $this->factoryStatus = $factoryStatus;
    }

    /**
     * 获取是否显示
     */
    public function getShowStatus(): int
    {
        return $this->showStatus;
    }

    /**
     * 设置是否显示
     */
    public function setShowStatus(int $showStatus): void
    {
        $this->showStatus = $showStatus;
    }

    /**
     * 获取产品数量
     */
    public function getProductCount(): int
    {
        return $this->productCount;
    }

    /**
     * 设置产品数量
     */
    public function setProductCount(int $productCount): void
    {
        $this->productCount = $productCount;
    }

    /**
     * 获取产品评论数量
     */
    public function getProductCommentCount(): int
    {
        return $this->productCommentCount;
    }

    /**
     * 设置产品评论数量
     */
    public function setProductCommentCount(int $productCommentCount): void
    {
        $this->productCommentCount = $productCommentCount;
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
