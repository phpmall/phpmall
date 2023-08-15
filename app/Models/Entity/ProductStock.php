<?php

declare(strict_types=1);

namespace App\Models\Entity;

use App\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductStockSchema')]
class ProductStock
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: '', type: 'int')]
    protected int $id;

    #[OA\Property(property: 'product_id', description: '商品id', type: 'int')]
    protected int $productId;

    #[OA\Property(property: 'sku_code', description: 'sku编码', type: 'string')]
    protected string $skuCode;

    #[OA\Property(property: 'price', description: '价格', type: 'float')]
    protected float $price;

    #[OA\Property(property: 'promotion_price', description: '单品促销价格', type: 'float')]
    protected float $promotionPrice;

    #[OA\Property(property: 'stock', description: '库存', type: 'int')]
    protected int $stock;

    #[OA\Property(property: 'low_stock', description: '预警库存', type: 'int')]
    protected int $lowStock;

    #[OA\Property(property: 'sp1', description: '规格属性1', type: 'string')]
    protected string $sp1;

    #[OA\Property(property: 'sp2', description: '规格属性2', type: 'string')]
    protected string $sp2;

    #[OA\Property(property: 'sp3', description: '规格属性3', type: 'string')]
    protected string $sp3;

    #[OA\Property(property: 'pic', description: '展示图片', type: 'string')]
    protected string $pic;

    #[OA\Property(property: 'sale', description: '销量', type: 'int')]
    protected int $sale;

    #[OA\Property(property: 'lock_stock', description: '锁定库存', type: 'int')]
    protected int $lockStock;

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
     * 获取商品id
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * 设置商品id
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * 获取sku编码
     */
    public function getSkuCode(): string
    {
        return $this->skuCode;
    }

    /**
     * 设置sku编码
     */
    public function setSkuCode(string $skuCode): void
    {
        $this->skuCode = $skuCode;
    }

    /**
     * 获取价格
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * 设置价格
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * 获取单品促销价格
     */
    public function getPromotionPrice(): float
    {
        return $this->promotionPrice;
    }

    /**
     * 设置单品促销价格
     */
    public function setPromotionPrice(float $promotionPrice): void
    {
        $this->promotionPrice = $promotionPrice;
    }

    /**
     * 获取库存
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * 设置库存
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * 获取预警库存
     */
    public function getLowStock(): int
    {
        return $this->lowStock;
    }

    /**
     * 设置预警库存
     */
    public function setLowStock(int $lowStock): void
    {
        $this->lowStock = $lowStock;
    }

    /**
     * 获取规格属性1
     */
    public function getSp1(): string
    {
        return $this->sp1;
    }

    /**
     * 设置规格属性1
     */
    public function setSp1(string $sp1): void
    {
        $this->sp1 = $sp1;
    }

    /**
     * 获取规格属性2
     */
    public function getSp2(): string
    {
        return $this->sp2;
    }

    /**
     * 设置规格属性2
     */
    public function setSp2(string $sp2): void
    {
        $this->sp2 = $sp2;
    }

    /**
     * 获取规格属性3
     */
    public function getSp3(): string
    {
        return $this->sp3;
    }

    /**
     * 设置规格属性3
     */
    public function setSp3(string $sp3): void
    {
        $this->sp3 = $sp3;
    }

    /**
     * 获取展示图片
     */
    public function getPic(): string
    {
        return $this->pic;
    }

    /**
     * 设置展示图片
     */
    public function setPic(string $pic): void
    {
        $this->pic = $pic;
    }

    /**
     * 获取销量
     */
    public function getSale(): int
    {
        return $this->sale;
    }

    /**
     * 设置销量
     */
    public function setSale(int $sale): void
    {
        $this->sale = $sale;
    }

    /**
     * 获取锁定库存
     */
    public function getLockStock(): int
    {
        return $this->lockStock;
    }

    /**
     * 设置锁定库存
     */
    public function setLockStock(int $lockStock): void
    {
        $this->lockStock = $lockStock;
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
