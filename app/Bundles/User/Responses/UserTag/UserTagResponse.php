<?php

declare(strict_types=1);

namespace App\Bundles\User\Responses\UserTag;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserTagResponse')]
class UserTagResponse
{
    use DTOHelper;

    #[OA\Property(property: 'tagId', description: '', type: 'integer')]
    private int $tagId;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'tagWords', description: '标签词', type: 'string')]
    private string $tagWords;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getTagId(): int
    {
        return $this->tagId;
    }

    /**
     * 设置
     */
    public function setTagId(int $tagId): void
    {
        $this->tagId = $tagId;
    }

    /**
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
     * 获取标签词
     */
    public function getTagWords(): string
    {
        return $this->tagWords;
    }

    /**
     * 设置标签词
     */
    public function setTagWords(string $tagWords): void
    {
        $this->tagWords = $tagWords;
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
