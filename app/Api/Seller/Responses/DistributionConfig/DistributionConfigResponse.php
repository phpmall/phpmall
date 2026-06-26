<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\DistributionConfig;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerDistributionConfigResponse')]
class DistributionConfigResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '配置ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'merchant_id', description: '商家ID', type: 'integer')]
    private int $merchantId;

    #[OA\Property(property: 'commission_type', description: '佣金类型:1按比例,2按固定金额', type: 'integer')]
    private int $commissionType;

    #[OA\Property(property: 'commission_rate', description: '佣金比例(万分之)', type: 'integer', nullable: true)]
    private ?int $commissionRate;

    #[OA\Property(property: 'level_config', description: '层级配置', type: 'object', nullable: true)]
    private ?array $levelConfig;

    #[OA\Property(property: 'status', description: '状态:0禁用,1启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string', format: 'date-time')]
    private string $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    public function setMerchantId(int $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function getCommissionType(): int
    {
        return $this->commissionType;
    }

    public function setCommissionType(int $commissionType): void
    {
        $this->commissionType = $commissionType;
    }

    public function getCommissionRate(): ?int
    {
        return $this->commissionRate;
    }

    public function setCommissionRate(?int $commissionRate): void
    {
        $this->commissionRate = $commissionRate;
    }

    public function getLevelConfig(): ?array
    {
        return $this->levelConfig;
    }

    public function setLevelConfig(?array $levelConfig): void
    {
        $this->levelConfig = $levelConfig;
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

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
