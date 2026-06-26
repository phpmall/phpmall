<?php

declare(strict_types=1);

namespace App\Api\Shop\Responses\Store;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopStoreResponse')]
class StoreResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '门店ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '门店名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'address', description: '门店地址', type: 'string')]
    private string $address;

    #[OA\Property(property: 'phone', description: '联系电话', type: 'string', nullable: true)]
    private ?string $phone;

    #[OA\Property(property: 'business_hours', description: '营业时间', type: 'string', nullable: true)]
    private ?string $businessHours;

    #[OA\Property(property: 'latitude', description: '纬度', type: 'number', format: 'float')]
    private float $latitude;

    #[OA\Property(property: 'longitude', description: '经度', type: 'number', format: 'float')]
    private float $longitude;

    #[OA\Property(property: 'city_id', description: '城市ID', type: 'integer', nullable: true)]
    private ?int $cityId;

    #[OA\Property(property: 'city_name', description: '城市名称', type: 'string', nullable: true)]
    private ?string $cityName;

    #[OA\Property(property: 'status', description: '状态:0关闭,1营业', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'distance', description: '距离(米)', type: 'number', format: 'float', nullable: true)]
    private ?float $distance;

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

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getBusinessHours(): ?string
    {
        return $this->businessHours;
    }

    public function setBusinessHours(?string $businessHours): void
    {
        $this->businessHours = $businessHours;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getCityId(): ?int
    {
        return $this->cityId;
    }

    public function setCityId(?int $cityId): void
    {
        $this->cityId = $cityId;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(?string $cityName): void
    {
        $this->cityName = $cityName;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(?float $distance): void
    {
        $this->distance = $distance;
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
