<?php

declare(strict_types=1);

namespace App\Bundles\User\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAddressEntity')]
class UserAddressEntity
{
    use DTOHelper;

    const string getAddressId = 'address_id';

    const string getAddressName = 'address_name'; // 地址名称

    const string getUserId = 'user_id'; // 用户ID

    const string getConsignee = 'consignee'; // 收货人

    const string getEmail = 'email'; // 邮箱

    const string getCountry = 'country'; // 国家

    const string getProvince = 'province'; // 省份

    const string getCity = 'city'; // 城市

    const string getDistrict = 'district'; // 区县

    const string getAddress = 'address'; // 详细地址

    const string getZipcode = 'zipcode'; // 邮编

    const string getTel = 'tel'; // 电话

    const string getMobile = 'mobile'; // 手机

    const string getSignBuilding = 'sign_building'; // 标志建筑

    const string getBestTime = 'best_time'; // 最佳送货时间

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'addressId', description: '', type: 'integer')]
    private int $addressId;

    #[OA\Property(property: 'addressName', description: '地址名称', type: 'string')]
    private string $addressName;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'consignee', description: '收货人', type: 'string')]
    private string $consignee;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string')]
    private string $email;

    #[OA\Property(property: 'country', description: '国家', type: 'integer')]
    private int $country;

    #[OA\Property(property: 'province', description: '省份', type: 'integer')]
    private int $province;

    #[OA\Property(property: 'city', description: '城市', type: 'integer')]
    private int $city;

    #[OA\Property(property: 'district', description: '区县', type: 'integer')]
    private int $district;

    #[OA\Property(property: 'address', description: '详细地址', type: 'string')]
    private string $address;

    #[OA\Property(property: 'zipcode', description: '邮编', type: 'string')]
    private string $zipcode;

    #[OA\Property(property: 'tel', description: '电话', type: 'string')]
    private string $tel;

    #[OA\Property(property: 'mobile', description: '手机', type: 'string')]
    private string $mobile;

    #[OA\Property(property: 'signBuilding', description: '标志建筑', type: 'string')]
    private string $signBuilding;

    #[OA\Property(property: 'bestTime', description: '最佳送货时间', type: 'string')]
    private string $bestTime;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getAddressId(): int
    {
        return $this->addressId;
    }

    /**
     * 设置
     */
    public function setAddressId(int $addressId): void
    {
        $this->addressId = $addressId;
    }

    /**
     * 获取地址名称
     */
    public function getAddressName(): string
    {
        return $this->addressName;
    }

    /**
     * 设置地址名称
     */
    public function setAddressName(string $addressName): void
    {
        $this->addressName = $addressName;
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
     * 获取收货人
     */
    public function getConsignee(): string
    {
        return $this->consignee;
    }

    /**
     * 设置收货人
     */
    public function setConsignee(string $consignee): void
    {
        $this->consignee = $consignee;
    }

    /**
     * 获取邮箱
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置邮箱
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取国家
     */
    public function getCountry(): int
    {
        return $this->country;
    }

    /**
     * 设置国家
     */
    public function setCountry(int $country): void
    {
        $this->country = $country;
    }

    /**
     * 获取省份
     */
    public function getProvince(): int
    {
        return $this->province;
    }

    /**
     * 设置省份
     */
    public function setProvince(int $province): void
    {
        $this->province = $province;
    }

    /**
     * 获取城市
     */
    public function getCity(): int
    {
        return $this->city;
    }

    /**
     * 设置城市
     */
    public function setCity(int $city): void
    {
        $this->city = $city;
    }

    /**
     * 获取区县
     */
    public function getDistrict(): int
    {
        return $this->district;
    }

    /**
     * 设置区县
     */
    public function setDistrict(int $district): void
    {
        $this->district = $district;
    }

    /**
     * 获取详细地址
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * 设置详细地址
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * 获取邮编
     */
    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    /**
     * 设置邮编
     */
    public function setZipcode(string $zipcode): void
    {
        $this->zipcode = $zipcode;
    }

    /**
     * 获取电话
     */
    public function getTel(): string
    {
        return $this->tel;
    }

    /**
     * 设置电话
     */
    public function setTel(string $tel): void
    {
        $this->tel = $tel;
    }

    /**
     * 获取手机
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * 设置手机
     */
    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * 获取标志建筑
     */
    public function getSignBuilding(): string
    {
        return $this->signBuilding;
    }

    /**
     * 设置标志建筑
     */
    public function setSignBuilding(string $signBuilding): void
    {
        $this->signBuilding = $signBuilding;
    }

    /**
     * 获取最佳送货时间
     */
    public function getBestTime(): string
    {
        return $this->bestTime;
    }

    /**
     * 设置最佳送货时间
     */
    public function setBestTime(string $bestTime): void
    {
        $this->bestTime = $bestTime;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedTime(): string
    {
        return $this->createdTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedTime(string $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedTime(): string
    {
        return $this->updatedTime;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedTime(string $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}
