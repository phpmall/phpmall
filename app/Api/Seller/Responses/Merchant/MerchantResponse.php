<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Merchant;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerMerchantResponse')]
class MerchantResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '商家ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '商家名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'logo', description: '商家Logo', type: 'string', nullable: true)]
    private ?string $logo;

    #[OA\Property(property: 'description', description: '商家描述', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'contact_name', description: '联系人姓名', type: 'string', nullable: true)]
    private ?string $contactName;

    #[OA\Property(property: 'contact_phone', description: '联系人电话', type: 'string', nullable: true)]
    private ?string $contactPhone;

    #[OA\Property(property: 'contact_email', description: '联系人邮箱', type: 'string', nullable: true)]
    private ?string $contactEmail;

    #[OA\Property(property: 'province', description: '省份', type: 'string', nullable: true)]
    private ?string $province;

    #[OA\Property(property: 'city', description: '城市', type: 'string', nullable: true)]
    private ?string $city;

    #[OA\Property(property: 'district', description: '区县', type: 'string', nullable: true)]
    private ?string $district;

    #[OA\Property(property: 'address', description: '详细地址', type: 'string', nullable: true)]
    private ?string $address;

    #[OA\Property(property: 'business_license', description: '营业执照编号', type: 'string', nullable: true)]
    private ?string $businessLicense;

    #[OA\Property(property: 'business_license_image', description: '营业执照图片', type: 'string', nullable: true)]
    private ?string $businessLicenseImage;

    #[OA\Property(property: 'status', description: '商家状态:0待审核,1已通过,2已拒绝,3已关闭', type: 'integer')]
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function setContactName(?string $contactName): void
    {
        $this->contactName = $contactName;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): void
    {
        $this->contactPhone = $contactPhone;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): void
    {
        $this->province = $province;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(?string $district): void
    {
        $this->district = $district;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getBusinessLicense(): ?string
    {
        return $this->businessLicense;
    }

    public function setBusinessLicense(?string $businessLicense): void
    {
        $this->businessLicense = $businessLicense;
    }

    public function getBusinessLicenseImage(): ?string
    {
        return $this->businessLicenseImage;
    }

    public function setBusinessLicenseImage(?string $businessLicenseImage): void
    {
        $this->businessLicenseImage = $businessLicenseImage;
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
