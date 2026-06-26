<?php

declare(strict_types=1);

namespace App\Api\User\Responses;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AddressResponse')]
class AddressResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '地址ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'contact_name', description: '联系人姓名', type: 'string')]
    private string $contactName;

    #[OA\Property(property: 'contact_phone', description: '联系人手机号', type: 'string')]
    private string $contactPhone;

    #[OA\Property(property: 'province', description: '省份', type: 'string')]
    private string $province;

    #[OA\Property(property: 'city', description: '城市', type: 'string')]
    private string $city;

    #[OA\Property(property: 'district', description: '区县', type: 'string')]
    private string $district;

    #[OA\Property(property: 'detail', description: '详细地址', type: 'string')]
    private string $detail;

    #[OA\Property(property: 'zip_code', description: '邮编', type: 'string', nullable: true)]
    private ?string $zipCode;

    #[OA\Property(property: 'is_default', description: '是否默认:0否，1是', type: 'integer')]
    private int $isDefault;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function setContactName(string $contactName): void
    {
        $this->contactName = $contactName;
    }

    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(string $contactPhone): void
    {
        $this->contactPhone = $contactPhone;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function setProvince(string $province): void
    {
        $this->province = $province;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getDistrict(): string
    {
        return $this->district;
    }

    public function setDistrict(string $district): void
    {
        $this->district = $district;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): void
    {
        $this->detail = $detail;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getIsDefault(): int
    {
        return $this->isDefault;
    }

    public function setIsDefault(int $isDefault): void
    {
        $this->isDefault = $isDefault;
    }
}
