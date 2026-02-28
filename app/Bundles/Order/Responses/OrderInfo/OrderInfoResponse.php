<?php

declare(strict_types=1);

namespace App\Bundles\Order\Responses\OrderInfo;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderInfoResponse')]
class OrderInfoResponse
{
    use DTOHelper;

    #[OA\Property(property: 'orderId', description: '', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'orderSn', description: '订单编号', type: 'string')]
    private string $orderSn;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'orderStatus', description: '订单状态', type: 'integer')]
    private int $orderStatus;

    #[OA\Property(property: 'shippingStatus', description: '配送状态', type: 'integer')]
    private int $shippingStatus;

    #[OA\Property(property: 'payStatus', description: '支付状态', type: 'integer')]
    private int $payStatus;

    #[OA\Property(property: 'consignee', description: '收货人', type: 'string')]
    private string $consignee;

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

    #[OA\Property(property: 'zipcode', description: '邮政编码', type: 'string')]
    private string $zipcode;

    #[OA\Property(property: 'tel', description: '电话', type: 'string')]
    private string $tel;

    #[OA\Property(property: 'mobile', description: '手机', type: 'string')]
    private string $mobile;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string')]
    private string $email;

    #[OA\Property(property: 'bestTime', description: '最佳送货时间', type: 'string')]
    private string $bestTime;

    #[OA\Property(property: 'signBuilding', description: '标志建筑', type: 'string')]
    private string $signBuilding;

    #[OA\Property(property: 'postscript', description: '订单附言', type: 'string')]
    private string $postscript;

    #[OA\Property(property: 'shippingId', description: '配送方式ID', type: 'integer')]
    private int $shippingId;

    #[OA\Property(property: 'shippingName', description: '配送方式名称', type: 'string')]
    private string $shippingName;

    #[OA\Property(property: 'payId', description: '支付方式ID', type: 'integer')]
    private int $payId;

    #[OA\Property(property: 'payName', description: '支付方式名称', type: 'string')]
    private string $payName;

    #[OA\Property(property: 'howOos', description: '缺货处理方式', type: 'string')]
    private string $howOos;

    #[OA\Property(property: 'howSurplus', description: '余额处理方式', type: 'string')]
    private string $howSurplus;

    #[OA\Property(property: 'packName', description: '包装名称', type: 'string')]
    private string $packName;

    #[OA\Property(property: 'cardName', description: '贺卡名称', type: 'string')]
    private string $cardName;

    #[OA\Property(property: 'cardMessage', description: '贺卡内容', type: 'string')]
    private string $cardMessage;

    #[OA\Property(property: 'invPayee', description: '发票抬头', type: 'string')]
    private string $invPayee;

    #[OA\Property(property: 'invContent', description: '发票内容', type: 'string')]
    private string $invContent;

    #[OA\Property(property: 'goodsAmount', description: '商品总金额', type: 'string')]
    private string $goodsAmount;

    #[OA\Property(property: 'shippingFee', description: '配送费用', type: 'string')]
    private string $shippingFee;

    #[OA\Property(property: 'insureFee', description: '保价费用', type: 'string')]
    private string $insureFee;

    #[OA\Property(property: 'payFee', description: '支付费用', type: 'string')]
    private string $payFee;

    #[OA\Property(property: 'packFee', description: '包装费用', type: 'string')]
    private string $packFee;

    #[OA\Property(property: 'cardFee', description: '贺卡费用', type: 'string')]
    private string $cardFee;

    #[OA\Property(property: 'moneyPaid', description: '已付款金额', type: 'string')]
    private string $moneyPaid;

    #[OA\Property(property: 'surplus', description: '余额', type: 'string')]
    private string $surplus;

    #[OA\Property(property: 'integral', description: '使用积分', type: 'integer')]
    private int $integral;

    #[OA\Property(property: 'integralMoney', description: '积分抵扣金额', type: 'string')]
    private string $integralMoney;

    #[OA\Property(property: 'bonus', description: '红包金额', type: 'string')]
    private string $bonus;

    #[OA\Property(property: 'orderAmount', description: '订单总金额', type: 'string')]
    private string $orderAmount;

    #[OA\Property(property: 'fromAd', description: '来源广告ID', type: 'integer')]
    private int $fromAd;

    #[OA\Property(property: 'referer', description: '来源页面', type: 'string')]
    private string $referer;

    #[OA\Property(property: 'addTime', description: '添加时间', type: 'integer')]
    private int $addTime;

    #[OA\Property(property: 'confirmTime', description: '确认时间', type: 'integer')]
    private int $confirmTime;

    #[OA\Property(property: 'payTime', description: '支付时间', type: 'integer')]
    private int $payTime;

    #[OA\Property(property: 'shippingTime', description: '配送时间', type: 'integer')]
    private int $shippingTime;

    #[OA\Property(property: 'packId', description: '包装ID', type: 'integer')]
    private int $packId;

    #[OA\Property(property: 'cardId', description: '贺卡ID', type: 'integer')]
    private int $cardId;

    #[OA\Property(property: 'bonusId', description: '红包ID', type: 'integer')]
    private int $bonusId;

    #[OA\Property(property: 'invoiceNo', description: '发货单号', type: 'string')]
    private string $invoiceNo;

    #[OA\Property(property: 'extensionCode', description: '扩展代码', type: 'string')]
    private string $extensionCode;

    #[OA\Property(property: 'extensionId', description: '扩展ID', type: 'integer')]
    private int $extensionId;

    #[OA\Property(property: 'toBuyer', description: '给买家的留言', type: 'string')]
    private string $toBuyer;

    #[OA\Property(property: 'payNote', description: '付款备注', type: 'string')]
    private string $payNote;

    #[OA\Property(property: 'agencyId', description: '办事处ID', type: 'integer')]
    private int $agencyId;

    #[OA\Property(property: 'invType', description: '发票类型', type: 'string')]
    private string $invType;

    #[OA\Property(property: 'tax', description: '税额', type: 'string')]
    private string $tax;

    #[OA\Property(property: 'isSeparate', description: '是否拆分', type: 'integer')]
    private int $isSeparate;

    #[OA\Property(property: 'parentId', description: '父订单ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'discount', description: '折扣金额', type: 'string')]
    private string $discount;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * 获取订单编号
     */
    public function getOrderSn(): string
    {
        return $this->orderSn;
    }

    /**
     * 设置订单编号
     */
    public function setOrderSn(string $orderSn): void
    {
        $this->orderSn = $orderSn;
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
     * 获取订单状态
     */
    public function getOrderStatus(): int
    {
        return $this->orderStatus;
    }

    /**
     * 设置订单状态
     */
    public function setOrderStatus(int $orderStatus): void
    {
        $this->orderStatus = $orderStatus;
    }

    /**
     * 获取配送状态
     */
    public function getShippingStatus(): int
    {
        return $this->shippingStatus;
    }

    /**
     * 设置配送状态
     */
    public function setShippingStatus(int $shippingStatus): void
    {
        $this->shippingStatus = $shippingStatus;
    }

    /**
     * 获取支付状态
     */
    public function getPayStatus(): int
    {
        return $this->payStatus;
    }

    /**
     * 设置支付状态
     */
    public function setPayStatus(int $payStatus): void
    {
        $this->payStatus = $payStatus;
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
     * 获取邮政编码
     */
    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    /**
     * 设置邮政编码
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
     * 获取订单附言
     */
    public function getPostscript(): string
    {
        return $this->postscript;
    }

    /**
     * 设置订单附言
     */
    public function setPostscript(string $postscript): void
    {
        $this->postscript = $postscript;
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
     * 获取支付方式ID
     */
    public function getPayId(): int
    {
        return $this->payId;
    }

    /**
     * 设置支付方式ID
     */
    public function setPayId(int $payId): void
    {
        $this->payId = $payId;
    }

    /**
     * 获取支付方式名称
     */
    public function getPayName(): string
    {
        return $this->payName;
    }

    /**
     * 设置支付方式名称
     */
    public function setPayName(string $payName): void
    {
        $this->payName = $payName;
    }

    /**
     * 获取缺货处理方式
     */
    public function getHowOos(): string
    {
        return $this->howOos;
    }

    /**
     * 设置缺货处理方式
     */
    public function setHowOos(string $howOos): void
    {
        $this->howOos = $howOos;
    }

    /**
     * 获取余额处理方式
     */
    public function getHowSurplus(): string
    {
        return $this->howSurplus;
    }

    /**
     * 设置余额处理方式
     */
    public function setHowSurplus(string $howSurplus): void
    {
        $this->howSurplus = $howSurplus;
    }

    /**
     * 获取包装名称
     */
    public function getPackName(): string
    {
        return $this->packName;
    }

    /**
     * 设置包装名称
     */
    public function setPackName(string $packName): void
    {
        $this->packName = $packName;
    }

    /**
     * 获取贺卡名称
     */
    public function getCardName(): string
    {
        return $this->cardName;
    }

    /**
     * 设置贺卡名称
     */
    public function setCardName(string $cardName): void
    {
        $this->cardName = $cardName;
    }

    /**
     * 获取贺卡内容
     */
    public function getCardMessage(): string
    {
        return $this->cardMessage;
    }

    /**
     * 设置贺卡内容
     */
    public function setCardMessage(string $cardMessage): void
    {
        $this->cardMessage = $cardMessage;
    }

    /**
     * 获取发票抬头
     */
    public function getInvPayee(): string
    {
        return $this->invPayee;
    }

    /**
     * 设置发票抬头
     */
    public function setInvPayee(string $invPayee): void
    {
        $this->invPayee = $invPayee;
    }

    /**
     * 获取发票内容
     */
    public function getInvContent(): string
    {
        return $this->invContent;
    }

    /**
     * 设置发票内容
     */
    public function setInvContent(string $invContent): void
    {
        $this->invContent = $invContent;
    }

    /**
     * 获取商品总金额
     */
    public function getGoodsAmount(): string
    {
        return $this->goodsAmount;
    }

    /**
     * 设置商品总金额
     */
    public function setGoodsAmount(string $goodsAmount): void
    {
        $this->goodsAmount = $goodsAmount;
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
     * 获取支付费用
     */
    public function getPayFee(): string
    {
        return $this->payFee;
    }

    /**
     * 设置支付费用
     */
    public function setPayFee(string $payFee): void
    {
        $this->payFee = $payFee;
    }

    /**
     * 获取包装费用
     */
    public function getPackFee(): string
    {
        return $this->packFee;
    }

    /**
     * 设置包装费用
     */
    public function setPackFee(string $packFee): void
    {
        $this->packFee = $packFee;
    }

    /**
     * 获取贺卡费用
     */
    public function getCardFee(): string
    {
        return $this->cardFee;
    }

    /**
     * 设置贺卡费用
     */
    public function setCardFee(string $cardFee): void
    {
        $this->cardFee = $cardFee;
    }

    /**
     * 获取已付款金额
     */
    public function getMoneyPaid(): string
    {
        return $this->moneyPaid;
    }

    /**
     * 设置已付款金额
     */
    public function setMoneyPaid(string $moneyPaid): void
    {
        $this->moneyPaid = $moneyPaid;
    }

    /**
     * 获取余额
     */
    public function getSurplus(): string
    {
        return $this->surplus;
    }

    /**
     * 设置余额
     */
    public function setSurplus(string $surplus): void
    {
        $this->surplus = $surplus;
    }

    /**
     * 获取使用积分
     */
    public function getIntegral(): int
    {
        return $this->integral;
    }

    /**
     * 设置使用积分
     */
    public function setIntegral(int $integral): void
    {
        $this->integral = $integral;
    }

    /**
     * 获取积分抵扣金额
     */
    public function getIntegralMoney(): string
    {
        return $this->integralMoney;
    }

    /**
     * 设置积分抵扣金额
     */
    public function setIntegralMoney(string $integralMoney): void
    {
        $this->integralMoney = $integralMoney;
    }

    /**
     * 获取红包金额
     */
    public function getBonus(): string
    {
        return $this->bonus;
    }

    /**
     * 设置红包金额
     */
    public function setBonus(string $bonus): void
    {
        $this->bonus = $bonus;
    }

    /**
     * 获取订单总金额
     */
    public function getOrderAmount(): string
    {
        return $this->orderAmount;
    }

    /**
     * 设置订单总金额
     */
    public function setOrderAmount(string $orderAmount): void
    {
        $this->orderAmount = $orderAmount;
    }

    /**
     * 获取来源广告ID
     */
    public function getFromAd(): int
    {
        return $this->fromAd;
    }

    /**
     * 设置来源广告ID
     */
    public function setFromAd(int $fromAd): void
    {
        $this->fromAd = $fromAd;
    }

    /**
     * 获取来源页面
     */
    public function getReferer(): string
    {
        return $this->referer;
    }

    /**
     * 设置来源页面
     */
    public function setReferer(string $referer): void
    {
        $this->referer = $referer;
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
     * 获取确认时间
     */
    public function getConfirmTime(): int
    {
        return $this->confirmTime;
    }

    /**
     * 设置确认时间
     */
    public function setConfirmTime(int $confirmTime): void
    {
        $this->confirmTime = $confirmTime;
    }

    /**
     * 获取支付时间
     */
    public function getPayTime(): int
    {
        return $this->payTime;
    }

    /**
     * 设置支付时间
     */
    public function setPayTime(int $payTime): void
    {
        $this->payTime = $payTime;
    }

    /**
     * 获取配送时间
     */
    public function getShippingTime(): int
    {
        return $this->shippingTime;
    }

    /**
     * 设置配送时间
     */
    public function setShippingTime(int $shippingTime): void
    {
        $this->shippingTime = $shippingTime;
    }

    /**
     * 获取包装ID
     */
    public function getPackId(): int
    {
        return $this->packId;
    }

    /**
     * 设置包装ID
     */
    public function setPackId(int $packId): void
    {
        $this->packId = $packId;
    }

    /**
     * 获取贺卡ID
     */
    public function getCardId(): int
    {
        return $this->cardId;
    }

    /**
     * 设置贺卡ID
     */
    public function setCardId(int $cardId): void
    {
        $this->cardId = $cardId;
    }

    /**
     * 获取红包ID
     */
    public function getBonusId(): int
    {
        return $this->bonusId;
    }

    /**
     * 设置红包ID
     */
    public function setBonusId(int $bonusId): void
    {
        $this->bonusId = $bonusId;
    }

    /**
     * 获取发货单号
     */
    public function getInvoiceNo(): string
    {
        return $this->invoiceNo;
    }

    /**
     * 设置发货单号
     */
    public function setInvoiceNo(string $invoiceNo): void
    {
        $this->invoiceNo = $invoiceNo;
    }

    /**
     * 获取扩展代码
     */
    public function getExtensionCode(): string
    {
        return $this->extensionCode;
    }

    /**
     * 设置扩展代码
     */
    public function setExtensionCode(string $extensionCode): void
    {
        $this->extensionCode = $extensionCode;
    }

    /**
     * 获取扩展ID
     */
    public function getExtensionId(): int
    {
        return $this->extensionId;
    }

    /**
     * 设置扩展ID
     */
    public function setExtensionId(int $extensionId): void
    {
        $this->extensionId = $extensionId;
    }

    /**
     * 获取给买家的留言
     */
    public function getToBuyer(): string
    {
        return $this->toBuyer;
    }

    /**
     * 设置给买家的留言
     */
    public function setToBuyer(string $toBuyer): void
    {
        $this->toBuyer = $toBuyer;
    }

    /**
     * 获取付款备注
     */
    public function getPayNote(): string
    {
        return $this->payNote;
    }

    /**
     * 设置付款备注
     */
    public function setPayNote(string $payNote): void
    {
        $this->payNote = $payNote;
    }

    /**
     * 获取办事处ID
     */
    public function getAgencyId(): int
    {
        return $this->agencyId;
    }

    /**
     * 设置办事处ID
     */
    public function setAgencyId(int $agencyId): void
    {
        $this->agencyId = $agencyId;
    }

    /**
     * 获取发票类型
     */
    public function getInvType(): string
    {
        return $this->invType;
    }

    /**
     * 设置发票类型
     */
    public function setInvType(string $invType): void
    {
        $this->invType = $invType;
    }

    /**
     * 获取税额
     */
    public function getTax(): string
    {
        return $this->tax;
    }

    /**
     * 设置税额
     */
    public function setTax(string $tax): void
    {
        $this->tax = $tax;
    }

    /**
     * 获取是否拆分
     */
    public function getIsSeparate(): int
    {
        return $this->isSeparate;
    }

    /**
     * 设置是否拆分
     */
    public function setIsSeparate(int $isSeparate): void
    {
        $this->isSeparate = $isSeparate;
    }

    /**
     * 获取父订单ID
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父订单ID
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取折扣金额
     */
    public function getDiscount(): string
    {
        return $this->discount;
    }

    /**
     * 设置折扣金额
     */
    public function setDiscount(string $discount): void
    {
        $this->discount = $discount;
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
