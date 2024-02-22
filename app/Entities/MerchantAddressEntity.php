<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MerchantAddressEntity')]
class MerchantAddressEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'merchant_id', description: '商户ID', type: 'integer')]
    protected int $merchant_id;

    #[OA\Property(property: 'name', description: '地址名称', type: 'string')]
    protected string $name;

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

    #[OA\Property(property: 'send_status', description: '默认发货地址：0->否；1->是', type: 'integer')]
    protected int $send_status;

    #[OA\Property(property: 'receive_status', description: '是否默认收货地址：0->否；1->是', type: 'integer')]
    protected int $receive_status;

    #[OA\Property(property: 'invoice_status', description: '默认收票地址：0->否；1->是', type: 'integer')]
    protected int $invoice_status;

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
     * 获取商户ID
     */
    public function getMerchantId(): int
    {
        return $this->merchant_id;
    }

    /**
     * 设置商户ID
     */
    public function setMerchantId(int $merchant_id): void
    {
        $this->merchant_id = $merchant_id;
    }

    /**
     * 获取地址名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置地址名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * 获取默认发货地址：0->否；1->是
     */
    public function getSendStatus(): int
    {
        return $this->send_status;
    }

    /**
     * 设置默认发货地址：0->否；1->是
     */
    public function setSendStatus(int $send_status): void
    {
        $this->send_status = $send_status;
    }

    /**
     * 获取是否默认收货地址：0->否；1->是
     */
    public function getReceiveStatus(): int
    {
        return $this->receive_status;
    }

    /**
     * 设置是否默认收货地址：0->否；1->是
     */
    public function setReceiveStatus(int $receive_status): void
    {
        $this->receive_status = $receive_status;
    }

    /**
     * 获取默认收票地址：0->否；1->是
     */
    public function getInvoiceStatus(): int
    {
        return $this->invoice_status;
    }

    /**
     * 设置默认收票地址：0->否；1->是
     */
    public function setInvoiceStatus(int $invoice_status): void
    {
        $this->invoice_status = $invoice_status;
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
