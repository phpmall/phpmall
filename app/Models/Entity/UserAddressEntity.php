<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAddressEntity')]
class UserAddressEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $userId;

    #[OA\Property(property: 'consignee', description: '收件人姓名', type: 'string')]
    protected string $consignee;

    #[OA\Property(property: 'mobile', description: '收件人电话', type: 'string')]
    protected string $mobile;

    #[OA\Property(property: 'country_name', description: '国家', type: 'string')]
    protected string $countryName;

    #[OA\Property(property: 'country_code', description: '国家编码', type: 'string')]
    protected string $countryCode;

    #[OA\Property(property: 'province_name', description: '省份', type: 'string')]
    protected string $provinceName;

    #[OA\Property(property: 'province_code', description: '省份编码', type: 'string')]
    protected string $provinceCode;

    #[OA\Property(property: 'city_name', description: '城市', type: 'string')]
    protected string $cityName;

    #[OA\Property(property: 'city_code', description: '城市编码', type: 'string')]
    protected string $cityCode;

    #[OA\Property(property: 'district_name', description: '区/县', type: 'string')]
    protected string $districtName;

    #[OA\Property(property: 'district_code', description: '区/县编码', type: 'string')]
    protected string $districtCode;

    #[OA\Property(property: 'detail_address', description: '详情地址', type: 'string')]
    protected string $detailAddress;

    #[OA\Property(property: 'is_default', description: '默认收货地址', type: 'integer')]
    protected int $isDefault;

    #[OA\Property(property: 'is_invoice', description: '默认收票地址', type: 'integer')]
    protected int $isInvoice;

    #[OA\Property(property: 'latitude', description: '纬度', type: 'string')]
    protected string $latitude;

    #[OA\Property(property: 'longitude', description: '经度', type: 'string')]
    protected string $longitude;

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
     * 获取收件人姓名
     */
    public function getConsignee(): string
    {
        return $this->consignee;
    }

    /**
     * 设置收件人姓名
     */
    public function setConsignee(string $consignee): void
    {
        $this->consignee = $consignee;
    }

    /**
     * 获取收件人电话
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * 设置收件人电话
     */
    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * 获取国家
     */
    public function getCountryName(): string
    {
        return $this->countryName;
    }

    /**
     * 设置国家
     */
    public function setCountryName(string $countryName): void
    {
        $this->countryName = $countryName;
    }

    /**
     * 获取国家编码
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * 设置国家编码
     */
    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * 获取省份
     */
    public function getProvinceName(): string
    {
        return $this->provinceName;
    }

    /**
     * 设置省份
     */
    public function setProvinceName(string $provinceName): void
    {
        $this->provinceName = $provinceName;
    }

    /**
     * 获取省份编码
     */
    public function getProvinceCode(): string
    {
        return $this->provinceCode;
    }

    /**
     * 设置省份编码
     */
    public function setProvinceCode(string $provinceCode): void
    {
        $this->provinceCode = $provinceCode;
    }

    /**
     * 获取城市
     */
    public function getCityName(): string
    {
        return $this->cityName;
    }

    /**
     * 设置城市
     */
    public function setCityName(string $cityName): void
    {
        $this->cityName = $cityName;
    }

    /**
     * 获取城市编码
     */
    public function getCityCode(): string
    {
        return $this->cityCode;
    }

    /**
     * 设置城市编码
     */
    public function setCityCode(string $cityCode): void
    {
        $this->cityCode = $cityCode;
    }

    /**
     * 获取区/县
     */
    public function getDistrictName(): string
    {
        return $this->districtName;
    }

    /**
     * 设置区/县
     */
    public function setDistrictName(string $districtName): void
    {
        $this->districtName = $districtName;
    }

    /**
     * 获取区/县编码
     */
    public function getDistrictCode(): string
    {
        return $this->districtCode;
    }

    /**
     * 设置区/县编码
     */
    public function setDistrictCode(string $districtCode): void
    {
        $this->districtCode = $districtCode;
    }

    /**
     * 获取详情地址
     */
    public function getDetailAddress(): string
    {
        return $this->detailAddress;
    }

    /**
     * 设置详情地址
     */
    public function setDetailAddress(string $detailAddress): void
    {
        $this->detailAddress = $detailAddress;
    }

    /**
     * 获取默认收货地址
     */
    public function getIsDefault(): int
    {
        return $this->isDefault;
    }

    /**
     * 设置默认收货地址
     */
    public function setIsDefault(int $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    /**
     * 获取默认收票地址
     */
    public function getIsInvoice(): int
    {
        return $this->isInvoice;
    }

    /**
     * 设置默认收票地址
     */
    public function setIsInvoice(int $isInvoice): void
    {
        $this->isInvoice = $isInvoice;
    }

    /**
     * 获取纬度
     */
    public function getLatitude(): string
    {
        return $this->latitude;
    }

    /**
     * 设置纬度
     */
    public function setLatitude(string $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * 获取经度
     */
    public function getLongitude(): string
    {
        return $this->longitude;
    }

    /**
     * 设置经度
     */
    public function setLongitude(string $longitude): void
    {
        $this->longitude = $longitude;
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
