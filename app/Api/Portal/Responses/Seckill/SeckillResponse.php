<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Seckill;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalSeckillResponse')]
class SeckillResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '秒杀ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '活动名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'product_id', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'product_name', description: '商品名称', type: 'string')]
    private string $productName;

    #[OA\Property(property: 'product_image', description: '商品图片', type: 'string')]
    private string $productImage;

    #[OA\Property(property: 'seckill_price', description: '秒杀价格(分)', type: 'integer')]
    private int $seckillPrice;

    #[OA\Property(property: 'original_price', description: '原价(分)', type: 'integer')]
    private int $originalPrice;

    #[OA\Property(property: 'stock', description: '秒杀库存', type: 'integer')]
    private int $stock;

    #[OA\Property(property: 'sold_count', description: '已售数量', type: 'integer')]
    private int $soldCount;

    #[OA\Property(property: 'start_time', description: '开始时间', type: 'string', format: 'date-time')]
    private string $startTime;

    #[OA\Property(property: 'end_time', description: '结束时间', type: 'string', format: 'date-time')]
    private string $endTime;

    #[OA\Property(property: 'status', description: '状态:0未开始,1进行中,2已结束', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    public function getProductImage(): string
    {
        return $this->productImage;
    }

    public function setProductImage(string $productImage): void
    {
        $this->productImage = $productImage;
    }

    public function getSeckillPrice(): int
    {
        return $this->seckillPrice;
    }

    public function setSeckillPrice(int $seckillPrice): void
    {
        $this->seckillPrice = $seckillPrice;
    }

    public function getOriginalPrice(): int
    {
        return $this->originalPrice;
    }

    public function setOriginalPrice(int $originalPrice): void
    {
        $this->originalPrice = $originalPrice;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getSoldCount(): int
    {
        return $this->soldCount;
    }

    public function setSoldCount(int $soldCount): void
    {
        $this->soldCount = $soldCount;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
