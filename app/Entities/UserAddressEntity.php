<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAddressEntity')]
class UserAddressEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'user_id', description: '用户ID', type: 'integer')]
    protected int $user_id;

    #[OA\Property(property: 'consignee', description: '收件人姓名', type: 'string')]
    protected string $consignee;

    #[OA\Property(property: 'mobile', description: '收件人电话', type: 'string')]
    protected string $mobile;

    #[OA\Property(property: 'country_name', description: '国家', type: 'string')]
    protected string $country_name;

    #[OA\Property(property: 'country_code', description: '国家编码', type: 'string')]
    protected string $country_code;

    #[OA\Property(property: 'province_name', description: '省份', type: 'string')]
    protected string $province_name;

    #[OA\Property(property: 'province_code', description: '省份编码', type: 'string')]
    protected string $province_code;

    #[OA\Property(property: 'city_name', description: '城市', type: 'string')]
    protected string $city_name;

    #[OA\Property(property: 'city_code', description: '城市编码', type: 'string')]
    protected string $city_code;

    #[OA\Property(property: 'district_name', description: '区/县', type: 'string')]
    protected string $district_name;

    #[OA\Property(property: 'district_code', description: '区/县编码', type: 'string')]
    protected string $district_code;

    #[OA\Property(property: 'detail_address', description: '详情地址', type: 'string')]
    protected string $detail_address;

    #[OA\Property(property: 'is_default', description: '默认收货地址', type: 'integer')]
    protected int $is_default;

    #[OA\Property(property: 'is_invoice', description: '默认收票地址', type: 'integer')]
    protected int $is_invoice;

    #[OA\Property(property: 'latitude', description: '纬度', type: 'string')]
    protected string $latitude;

    #[OA\Property(property: 'longitude', description: '经度', type: 'string')]
    protected string $longitude;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deleted_at;

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
        return $this->user_id;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
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
        return $this->country_name;
    }

    /**
     * 设置国家
     */
    public function setCountryName(string $country_name): void
    {
        $this->country_name = $country_name;
    }

    /**
     * 获取国家编码
     */
    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    /**
     * 设置国家编码
     */
    public function setCountryCode(string $country_code): void
    {
        $this->country_code = $country_code;
    }

    /**
     * 获取省份
     */
    public function getProvinceName(): string
    {
        return $this->province_name;
    }

    /**
     * 设置省份
     */
    public function setProvinceName(string $province_name): void
    {
        $this->province_name = $province_name;
    }

    /**
     * 获取省份编码
     */
    public function getProvinceCode(): string
    {
        return $this->province_code;
    }

    /**
     * 设置省份编码
     */
    public function setProvinceCode(string $province_code): void
    {
        $this->province_code = $province_code;
    }

    /**
     * 获取城市
     */
    public function getCityName(): string
    {
        return $this->city_name;
    }

    /**
     * 设置城市
     */
    public function setCityName(string $city_name): void
    {
        $this->city_name = $city_name;
    }

    /**
     * 获取城市编码
     */
    public function getCityCode(): string
    {
        return $this->city_code;
    }

    /**
     * 设置城市编码
     */
    public function setCityCode(string $city_code): void
    {
        $this->city_code = $city_code;
    }

    /**
     * 获取区/县
     */
    public function getDistrictName(): string
    {
        return $this->district_name;
    }

    /**
     * 设置区/县
     */
    public function setDistrictName(string $district_name): void
    {
        $this->district_name = $district_name;
    }

    /**
     * 获取区/县编码
     */
    public function getDistrictCode(): string
    {
        return $this->district_code;
    }

    /**
     * 设置区/县编码
     */
    public function setDistrictCode(string $district_code): void
    {
        $this->district_code = $district_code;
    }

    /**
     * 获取详情地址
     */
    public function getDetailAddress(): string
    {
        return $this->detail_address;
    }

    /**
     * 设置详情地址
     */
    public function setDetailAddress(string $detail_address): void
    {
        $this->detail_address = $detail_address;
    }

    /**
     * 获取默认收货地址
     */
    public function getIsDefault(): int
    {
        return $this->is_default;
    }

    /**
     * 设置默认收货地址
     */
    public function setIsDefault(int $is_default): void
    {
        $this->is_default = $is_default;
    }

    /**
     * 获取默认收票地址
     */
    public function getIsInvoice(): int
    {
        return $this->is_invoice;
    }

    /**
     * 设置默认收票地址
     */
    public function setIsInvoice(int $is_invoice): void
    {
        $this->is_invoice = $is_invoice;
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
        return $this->created_at;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deleted_at;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }
}
