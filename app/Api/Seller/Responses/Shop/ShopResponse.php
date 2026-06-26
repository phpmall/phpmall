<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Shop;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerShopResponse')]
class ShopResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '店铺ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '店铺名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'logo', description: '店铺Logo', type: 'string', nullable: true)]
    private ?string $logo;

    #[OA\Property(property: 'banner', description: '店铺Banner', type: 'string', nullable: true)]
    private ?string $banner;

    #[OA\Property(property: 'description', description: '店铺描述', type: 'string', nullable: true)]
    private ?string $description;

    #[OA\Property(property: 'notice', description: '店铺公告', type: 'string', nullable: true)]
    private ?string $notice;

    #[OA\Property(property: 'contact_phone', description: '联系电话', type: 'string', nullable: true)]
    private ?string $contactPhone;

    #[OA\Property(property: 'contact_email', description: '联系邮箱', type: 'string', nullable: true)]
    private ?string $contactEmail;

    #[OA\Property(property: 'province', description: '省份', type: 'string', nullable: true)]
    private ?string $province;

    #[OA\Property(property: 'city', description: '城市', type: 'string', nullable: true)]
    private ?string $city;

    #[OA\Property(property: 'district', description: '区县', type: 'string', nullable: true)]
    private ?string $district;

    #[OA\Property(property: 'address', description: '详细地址', type: 'string', nullable: true)]
    private ?string $address;

    #[OA\Property(property: 'status', description: '店铺状态:0关闭,1营业中', type: 'integer')]
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

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): void
    {
        $this->banner = $banner;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getNotice(): ?string
    {
        return $this->notice;
    }

    public function setNotice(?string $notice): void
    {
        $this->notice = $notice;
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
