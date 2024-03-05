<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
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
    protected string $first_letter;

    #[OA\Property(property: 'logo', description: '品牌的标识性Logo图片地址', type: 'string')]
    protected string $logo;

    #[OA\Property(property: 'big_pic', description: '专区大图', type: 'string')]
    protected string $big_pic;

    #[OA\Property(property: 'brand_story', description: '品牌故事', type: 'string')]
    protected string $brand_story;

    #[OA\Property(property: 'factory_status', description: '是否为品牌制造商：0->不是；1->是', type: 'integer')]
    protected int $factory_status;

    #[OA\Property(property: 'show_status', description: '是否显示', type: 'integer')]
    protected int $show_status;

    #[OA\Property(property: 'product_count', description: '产品数量', type: 'integer')]
    protected int $product_count;

    #[OA\Property(property: 'product_comment_count', description: '产品评论数量', type: 'integer')]
    protected int $product_comment_count;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

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
        return $this->first_letter;
    }

    /**
     * 设置品牌名称的首字母
     */
    public function setFirstLetter(string $first_letter): void
    {
        $this->first_letter = $first_letter;
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
        return $this->big_pic;
    }

    /**
     * 设置专区大图
     */
    public function setBigPic(string $big_pic): void
    {
        $this->big_pic = $big_pic;
    }

    /**
     * 获取品牌故事
     */
    public function getBrandStory(): string
    {
        return $this->brand_story;
    }

    /**
     * 设置品牌故事
     */
    public function setBrandStory(string $brand_story): void
    {
        $this->brand_story = $brand_story;
    }

    /**
     * 获取是否为品牌制造商：0->不是；1->是
     */
    public function getFactoryStatus(): int
    {
        return $this->factory_status;
    }

    /**
     * 设置是否为品牌制造商：0->不是；1->是
     */
    public function setFactoryStatus(int $factory_status): void
    {
        $this->factory_status = $factory_status;
    }

    /**
     * 获取是否显示
     */
    public function getShowStatus(): int
    {
        return $this->show_status;
    }

    /**
     * 设置是否显示
     */
    public function setShowStatus(int $show_status): void
    {
        $this->show_status = $show_status;
    }

    /**
     * 获取产品数量
     */
    public function getProductCount(): int
    {
        return $this->product_count;
    }

    /**
     * 设置产品数量
     */
    public function setProductCount(int $product_count): void
    {
        $this->product_count = $product_count;
    }

    /**
     * 获取产品评论数量
     */
    public function getProductCommentCount(): int
    {
        return $this->product_comment_count;
    }

    /**
     * 设置产品评论数量
     */
    public function setProductCommentCount(int $product_comment_count): void
    {
        $this->product_comment_count = $product_comment_count;
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
