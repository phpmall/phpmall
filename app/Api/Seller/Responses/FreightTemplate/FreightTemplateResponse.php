<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\FreightTemplate;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerFreightTemplateResponse')]
class FreightTemplateResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '模板ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shop_id', description: '店铺ID', type: 'integer')]
    private int $shopId;

    #[OA\Property(property: 'name', description: '模板名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'pricing_type', description: '计价方式:1按件数,2按重量,3按体积', type: 'integer')]
    private int $pricingType;

    #[OA\Property(property: 'is_free_shipping', description: '是否包邮:0否,1是', type: 'integer')]
    private int $isFreeShipping;

    #[OA\Property(property: 'free_shipping_threshold', description: '包邮门槛金额(分)', type: 'integer', nullable: true)]
    private ?int $freeShippingThreshold;

    #[OA\Property(property: 'default_first_unit', description: '默认首件/首重/首体积', type: 'integer')]
    private int $defaultFirstUnit;

    #[OA\Property(property: 'default_first_fee', description: '默认首费(分)', type: 'integer')]
    private int $defaultFirstFee;

    #[OA\Property(property: 'default_continue_unit', description: '默认续件/续重/续体积', type: 'integer')]
    private int $defaultContinueUnit;

    #[OA\Property(property: 'default_continue_fee', description: '默认续费(分)', type: 'integer')]
    private int $defaultContinueFee;

    #[OA\Property(
        property: 'region_rules',
        description: '区域运费规则',
        type: 'array',
        items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'region_ids', type: 'array', items: new OA\Items(type: 'integer')),
            new OA\Property(property: 'region_names', type: 'array', items: new OA\Items(type: 'string')),
            new OA\Property(property: 'first_unit', type: 'integer'),
            new OA\Property(property: 'first_fee', type: 'integer', description: '首费(分)'),
            new OA\Property(property: 'continue_unit', type: 'integer'),
            new OA\Property(property: 'continue_fee', type: 'integer', description: '续费(分)'),
        ])
    )]
    private array $regionRules;

    #[OA\Property(property: 'is_default', description: '是否默认模板:0否,1是', type: 'integer')]
    private int $isDefault;

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

    public function getShopId(): int
    {
        return $this->shopId;
    }

    public function setShopId(int $shopId): void
    {
        $this->shopId = $shopId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPricingType(): int
    {
        return $this->pricingType;
    }

    public function setPricingType(int $pricingType): void
    {
        $this->pricingType = $pricingType;
    }

    public function getIsFreeShipping(): int
    {
        return $this->isFreeShipping;
    }

    public function setIsFreeShipping(int $isFreeShipping): void
    {
        $this->isFreeShipping = $isFreeShipping;
    }

    public function getFreeShippingThreshold(): ?int
    {
        return $this->freeShippingThreshold;
    }

    public function setFreeShippingThreshold(?int $freeShippingThreshold): void
    {
        $this->freeShippingThreshold = $freeShippingThreshold;
    }

    public function getDefaultFirstUnit(): int
    {
        return $this->defaultFirstUnit;
    }

    public function setDefaultFirstUnit(int $defaultFirstUnit): void
    {
        $this->defaultFirstUnit = $defaultFirstUnit;
    }

    public function getDefaultFirstFee(): int
    {
        return $this->defaultFirstFee;
    }

    public function setDefaultFirstFee(int $defaultFirstFee): void
    {
        $this->defaultFirstFee = $defaultFirstFee;
    }

    public function getDefaultContinueUnit(): int
    {
        return $this->defaultContinueUnit;
    }

    public function setDefaultContinueUnit(int $defaultContinueUnit): void
    {
        $this->defaultContinueUnit = $defaultContinueUnit;
    }

    public function getDefaultContinueFee(): int
    {
        return $this->defaultContinueFee;
    }

    public function setDefaultContinueFee(int $defaultContinueFee): void
    {
        $this->defaultContinueFee = $defaultContinueFee;
    }

    public function getRegionRules(): array
    {
        return $this->regionRules;
    }

    public function setRegionRules(array $regionRules): void
    {
        $this->regionRules = $regionRules;
    }

    public function getIsDefault(): int
    {
        return $this->isDefault;
    }

    public function setIsDefault(int $isDefault): void
    {
        $this->isDefault = $isDefault;
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
