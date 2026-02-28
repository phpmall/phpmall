<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Responses\GoodsGallery;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'GoodsGalleryResponse')]
class GoodsGalleryResponse
{
    use DTOHelper;

    #[OA\Property(property: 'imgId', description: '', type: 'integer')]
    private int $imgId;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'imgUrl', description: '图片URL', type: 'string')]
    private string $imgUrl;

    #[OA\Property(property: 'imgDesc', description: '图片描述', type: 'string')]
    private string $imgDesc;

    #[OA\Property(property: 'thumbUrl', description: '缩略图URL', type: 'string')]
    private string $thumbUrl;

    #[OA\Property(property: 'imgOriginal', description: '原始图片', type: 'string')]
    private string $imgOriginal;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getImgId(): int
    {
        return $this->imgId;
    }

    /**
     * 设置
     */
    public function setImgId(int $imgId): void
    {
        $this->imgId = $imgId;
    }

    /**
     * 获取商品ID
     */
    public function getGoodsId(): int
    {
        return $this->goodsId;
    }

    /**
     * 设置商品ID
     */
    public function setGoodsId(int $goodsId): void
    {
        $this->goodsId = $goodsId;
    }

    /**
     * 获取图片URL
     */
    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    /**
     * 设置图片URL
     */
    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    /**
     * 获取图片描述
     */
    public function getImgDesc(): string
    {
        return $this->imgDesc;
    }

    /**
     * 设置图片描述
     */
    public function setImgDesc(string $imgDesc): void
    {
        $this->imgDesc = $imgDesc;
    }

    /**
     * 获取缩略图URL
     */
    public function getThumbUrl(): string
    {
        return $this->thumbUrl;
    }

    /**
     * 设置缩略图URL
     */
    public function setThumbUrl(string $thumbUrl): void
    {
        $this->thumbUrl = $thumbUrl;
    }

    /**
     * 获取原始图片
     */
    public function getImgOriginal(): string
    {
        return $this->imgOriginal;
    }

    /**
     * 设置原始图片
     */
    public function setImgOriginal(string $imgOriginal): void
    {
        $this->imgOriginal = $imgOriginal;
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
