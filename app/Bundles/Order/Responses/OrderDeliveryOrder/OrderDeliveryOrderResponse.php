<?php

declare(strict_types=1);

namespace App\Bundles\Order\Responses\OrderDeliveryOrder;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderDeliveryOrderResponse')]
class OrderDeliveryOrderResponse
{
    use DTOHelper;

    #[OA\Property(property: 'deliveryId', description: '', type: 'integer')]
    private int $deliveryId;

    #[OA\Property(property: 'deliverySn', description: '发货单号', type: 'string')]
    private string $deliverySn;

    #[OA\Property(property: 'orderSn', description: '订单号', type: 'string')]
    private string $orderSn;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'invoiceNo', description: '物流单号', type: 'string')]
    private string $invoiceNo;

    #[OA\Property(property: 'addTime', description: '添加时间', type: 'integer')]
    private int $addTime;

    #[OA\Property(property: 'shippingId', description: '配送方式ID', type: 'integer')]
    private int $shippingId;

    #[OA\Property(property: 'shippingName', description: '配送方式名称', type: 'string')]
    private string $shippingName;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'actionUser', description: '操作用户', type: 'string')]
    private string $actionUser;

    #[OA\Property(property: 'consignee', description: '收货人', type: 'string')]
    private string $consignee;

    #[OA\Property(property: 'address', description: '详细地址', type: 'string')]
    private string $address;

    #[OA\Property(property: 'country', description: '国家', type: 'integer')]
    private int $country;

    #[OA\Property(property: 'province', description: '省份', type: 'integer')]
    private int $province;

    #[OA\Property(property: 'city', description: '城市', type: 'integer')]
    private int $city;

    #[OA\Property(property: 'district', description: '区县', type: 'integer')]
    private int $district;

    #[OA\Property(property: 'signBuilding', description: '标志建筑', type: 'string')]
    private string $signBuilding;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string')]
    private string $email;

    #[OA\Property(property: 'zipcode', description: '邮编', type: 'string')]
    private string $zipcode;

    #[OA\Property(property: 'tel', description: '电话', type: 'string')]
    private string $tel;

    #[OA\Property(property: 'mobile', description: '手机', type: 'string')]
    private string $mobile;

    #[OA\Property(property: 'bestTime', description: '最佳送货时间', type: 'string')]
    private string $bestTime;

    #[OA\Property(property: 'postscript', description: '附言', type: 'string')]
    private string $postscript;

    #[OA\Property(property: 'howOos', description: '缺货处理', type: 'string')]
    private string $howOos;

    #[OA\Property(property: 'insureFee', description: '保价费用', type: 'string')]
    private string $insureFee;

    #[OA\Property(property: 'shippingFee', description: '配送费用', type: 'string')]
    private string $shippingFee;

    #[OA\Property(property: 'updateTime', description: '更新时间戳', type: 'integer')]
    private int $updateTime;

    #[OA\Property(property: 'suppliersId', description: '供应商ID', type: 'integer')]
    private int $suppliersId;

    #[OA\Property(property: 'status', description: '状态', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'agencyId', description: '代理商ID', type: 'integer')]
    private int $agencyId;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getDeliveryId(): int
    {
        return $this->deliveryId;
    }

    /**
     * 设置
     */
    public function setDeliveryId(int $deliveryId): void
    {
        $this->deliveryId = $deliveryId;
    }

    /**
     * 获取发货单号
     */
    public function getDeliverySn(): string
    {
        return $this->deliverySn;
    }

    /**
     * 设置发货单号
     */
    public function setDeliverySn(string $deliverySn): void
    {
        $this->deliverySn = $deliverySn;
    }

    /**
     * 获取订单号
     */
    public function getOrderSn(): string
    {
        return $this->orderSn;
    }

    /**
     * 设置订单号
     */
    public function setOrderSn(string $orderSn): void
    {
        $this->orderSn = $orderSn;
    }

    /**
     * 获取订单ID
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置订单ID
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * 获取物流单号
     */
    public function getInvoiceNo(): string
    {
        return $this->invoiceNo;
    }

    /**
     * 设置物流单号
     */
    public function setInvoiceNo(string $invoiceNo): void
    {
        $this->invoiceNo = $invoiceNo;
    }

    /**
     * 获取添加时间
     */
    public function getAddTime(): int
    {
        return $this->addTime;
    }

    /**
     * 设置添加时间
     */
    public function setAddTime(int $addTime): void
    {
        $this->addTime = $addTime;
    }

    /**
     * 获取配送方式ID
     */
    public function getShippingId(): int
    {
        return $this->shippingId;
    }

    /**
     * 设置配送方式ID
     */
    public function setShippingId(int $shippingId): void
    {
        $this->shippingId = $shippingId;
    }

    /**
     * 获取配送方式名称
     */
    public function getShippingName(): string
    {
        return $this->shippingName;
    }

    /**
     * 设置配送方式名称
     */
    public function setShippingName(string $shippingName): void
    {
        $this->shippingName = $shippingName;
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
     * 获取操作用户
     */
    public function getActionUser(): string
    {
        return $this->actionUser;
    }

    /**
     * 设置操作用户
     */
    public function setActionUser(string $actionUser): void
    {
        $this->actionUser = $actionUser;
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
     * 获取附言
     */
    public function getPostscript(): string
    {
        return $this->postscript;
    }

    /**
     * 设置附言
     */
    public function setPostscript(string $postscript): void
    {
        $this->postscript = $postscript;
    }

    /**
     * 获取缺货处理
     */
    public function getHowOos(): string
    {
        return $this->howOos;
    }

    /**
     * 设置缺货处理
     */
    public function setHowOos(string $howOos): void
    {
        $this->howOos = $howOos;
    }

    /**
     * 获取保价费用
     */
    public function getInsureFee(): string
    {
        return $this->insureFee;
    }

    /**
     * 设置保价费用
     */
    public function setInsureFee(string $insureFee): void
    {
        $this->insureFee = $insureFee;
    }

    /**
     * 获取配送费用
     */
    public function getShippingFee(): string
    {
        return $this->shippingFee;
    }

    /**
     * 设置配送费用
     */
    public function setShippingFee(string $shippingFee): void
    {
        $this->shippingFee = $shippingFee;
    }

    /**
     * 获取更新时间戳
     */
    public function getUpdateTime(): int
    {
        return $this->updateTime;
    }

    /**
     * 设置更新时间戳
     */
    public function setUpdateTime(int $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    /**
     * 获取供应商ID
     */
    public function getSuppliersId(): int
    {
        return $this->suppliersId;
    }

    /**
     * 设置供应商ID
     */
    public function setSuppliersId(int $suppliersId): void
    {
        $this->suppliersId = $suppliersId;
    }

    /**
     * 获取状态
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取代理商ID
     */
    public function getAgencyId(): int
    {
        return $this->agencyId;
    }

    /**
     * 设置代理商ID
     */
    public function setAgencyId(int $agencyId): void
    {
        $this->agencyId = $agencyId;
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
