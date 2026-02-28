<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderInfo;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderInfoCreateRequest',
    required: [
        self::getOrderId,
        self::getOrderSn,
        self::getUserId,
        self::getOrderStatus,
        self::getShippingStatus,
        self::getPayStatus,
        self::getConsignee,
        self::getCountry,
        self::getProvince,
        self::getCity,
        self::getDistrict,
        self::getAddress,
        self::getZipcode,
        self::getTel,
        self::getMobile,
        self::getEmail,
        self::getBestTime,
        self::getSignBuilding,
        self::getPostscript,
        self::getShippingId,
        self::getShippingName,
        self::getPayId,
        self::getPayName,
        self::getHowOos,
        self::getHowSurplus,
        self::getPackName,
        self::getCardName,
        self::getCardMessage,
        self::getInvPayee,
        self::getInvContent,
        self::getGoodsAmount,
        self::getShippingFee,
        self::getInsureFee,
        self::getPayFee,
        self::getPackFee,
        self::getCardFee,
        self::getMoneyPaid,
        self::getSurplus,
        self::getIntegral,
        self::getIntegralMoney,
        self::getBonus,
        self::getOrderAmount,
        self::getFromAd,
        self::getReferer,
        self::getAddTime,
        self::getConfirmTime,
        self::getPayTime,
        self::getShippingTime,
        self::getPackId,
        self::getCardId,
        self::getBonusId,
        self::getInvoiceNo,
        self::getExtensionCode,
        self::getExtensionId,
        self::getToBuyer,
        self::getPayNote,
        self::getAgencyId,
        self::getInvType,
        self::getTax,
        self::getIsSeparate,
        self::getParentId,
        self::getDiscount,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '', type: 'integer'),
        new OA\Property(property: self::getOrderSn, description: '订单编号', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getOrderStatus, description: '订单状态', type: 'integer'),
        new OA\Property(property: self::getShippingStatus, description: '配送状态', type: 'integer'),
        new OA\Property(property: self::getPayStatus, description: '支付状态', type: 'integer'),
        new OA\Property(property: self::getConsignee, description: '收货人', type: 'string'),
        new OA\Property(property: self::getCountry, description: '国家', type: 'integer'),
        new OA\Property(property: self::getProvince, description: '省份', type: 'integer'),
        new OA\Property(property: self::getCity, description: '城市', type: 'integer'),
        new OA\Property(property: self::getDistrict, description: '区县', type: 'integer'),
        new OA\Property(property: self::getAddress, description: '详细地址', type: 'string'),
        new OA\Property(property: self::getZipcode, description: '邮政编码', type: 'string'),
        new OA\Property(property: self::getTel, description: '电话', type: 'string'),
        new OA\Property(property: self::getMobile, description: '手机', type: 'string'),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string'),
        new OA\Property(property: self::getBestTime, description: '最佳送货时间', type: 'string'),
        new OA\Property(property: self::getSignBuilding, description: '标志建筑', type: 'string'),
        new OA\Property(property: self::getPostscript, description: '订单附言', type: 'string'),
        new OA\Property(property: self::getShippingId, description: '配送方式ID', type: 'integer'),
        new OA\Property(property: self::getShippingName, description: '配送方式名称', type: 'string'),
        new OA\Property(property: self::getPayId, description: '支付方式ID', type: 'integer'),
        new OA\Property(property: self::getPayName, description: '支付方式名称', type: 'string'),
        new OA\Property(property: self::getHowOos, description: '缺货处理方式', type: 'string'),
        new OA\Property(property: self::getHowSurplus, description: '余额处理方式', type: 'string'),
        new OA\Property(property: self::getPackName, description: '包装名称', type: 'string'),
        new OA\Property(property: self::getCardName, description: '贺卡名称', type: 'string'),
        new OA\Property(property: self::getCardMessage, description: '贺卡内容', type: 'string'),
        new OA\Property(property: self::getInvPayee, description: '发票抬头', type: 'string'),
        new OA\Property(property: self::getInvContent, description: '发票内容', type: 'string'),
        new OA\Property(property: self::getGoodsAmount, description: '商品总金额', type: 'string'),
        new OA\Property(property: self::getShippingFee, description: '配送费用', type: 'string'),
        new OA\Property(property: self::getInsureFee, description: '保价费用', type: 'string'),
        new OA\Property(property: self::getPayFee, description: '支付费用', type: 'string'),
        new OA\Property(property: self::getPackFee, description: '包装费用', type: 'string'),
        new OA\Property(property: self::getCardFee, description: '贺卡费用', type: 'string'),
        new OA\Property(property: self::getMoneyPaid, description: '已付款金额', type: 'string'),
        new OA\Property(property: self::getSurplus, description: '余额', type: 'string'),
        new OA\Property(property: self::getIntegral, description: '使用积分', type: 'integer'),
        new OA\Property(property: self::getIntegralMoney, description: '积分抵扣金额', type: 'string'),
        new OA\Property(property: self::getBonus, description: '红包金额', type: 'string'),
        new OA\Property(property: self::getOrderAmount, description: '订单总金额', type: 'string'),
        new OA\Property(property: self::getFromAd, description: '来源广告ID', type: 'integer'),
        new OA\Property(property: self::getReferer, description: '来源页面', type: 'string'),
        new OA\Property(property: self::getAddTime, description: '添加时间', type: 'integer'),
        new OA\Property(property: self::getConfirmTime, description: '确认时间', type: 'integer'),
        new OA\Property(property: self::getPayTime, description: '支付时间', type: 'integer'),
        new OA\Property(property: self::getShippingTime, description: '配送时间', type: 'integer'),
        new OA\Property(property: self::getPackId, description: '包装ID', type: 'integer'),
        new OA\Property(property: self::getCardId, description: '贺卡ID', type: 'integer'),
        new OA\Property(property: self::getBonusId, description: '红包ID', type: 'integer'),
        new OA\Property(property: self::getInvoiceNo, description: '发货单号', type: 'string'),
        new OA\Property(property: self::getExtensionCode, description: '扩展代码', type: 'string'),
        new OA\Property(property: self::getExtensionId, description: '扩展ID', type: 'integer'),
        new OA\Property(property: self::getToBuyer, description: '给买家的留言', type: 'string'),
        new OA\Property(property: self::getPayNote, description: '付款备注', type: 'string'),
        new OA\Property(property: self::getAgencyId, description: '办事处ID', type: 'integer'),
        new OA\Property(property: self::getInvType, description: '发票类型', type: 'string'),
        new OA\Property(property: self::getTax, description: '税额', type: 'string'),
        new OA\Property(property: self::getIsSeparate, description: '是否拆分', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父订单ID', type: 'integer'),
        new OA\Property(property: self::getDiscount, description: '折扣金额', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class OrderInfoCreateRequest extends FormRequest
{
    const string getOrderId = 'orderId';

    const string getOrderSn = 'orderSn';

    const string getUserId = 'userId';

    const string getOrderStatus = 'orderStatus';

    const string getShippingStatus = 'shippingStatus';

    const string getPayStatus = 'payStatus';

    const string getConsignee = 'consignee';

    const string getCountry = 'country';

    const string getProvince = 'province';

    const string getCity = 'city';

    const string getDistrict = 'district';

    const string getAddress = 'address';

    const string getZipcode = 'zipcode';

    const string getTel = 'tel';

    const string getMobile = 'mobile';

    const string getEmail = 'email';

    const string getBestTime = 'bestTime';

    const string getSignBuilding = 'signBuilding';

    const string getPostscript = 'postscript';

    const string getShippingId = 'shippingId';

    const string getShippingName = 'shippingName';

    const string getPayId = 'payId';

    const string getPayName = 'payName';

    const string getHowOos = 'howOos';

    const string getHowSurplus = 'howSurplus';

    const string getPackName = 'packName';

    const string getCardName = 'cardName';

    const string getCardMessage = 'cardMessage';

    const string getInvPayee = 'invPayee';

    const string getInvContent = 'invContent';

    const string getGoodsAmount = 'goodsAmount';

    const string getShippingFee = 'shippingFee';

    const string getInsureFee = 'insureFee';

    const string getPayFee = 'payFee';

    const string getPackFee = 'packFee';

    const string getCardFee = 'cardFee';

    const string getMoneyPaid = 'moneyPaid';

    const string getSurplus = 'surplus';

    const string getIntegral = 'integral';

    const string getIntegralMoney = 'integralMoney';

    const string getBonus = 'bonus';

    const string getOrderAmount = 'orderAmount';

    const string getFromAd = 'fromAd';

    const string getReferer = 'referer';

    const string getAddTime = 'addTime';

    const string getConfirmTime = 'confirmTime';

    const string getPayTime = 'payTime';

    const string getShippingTime = 'shippingTime';

    const string getPackId = 'packId';

    const string getCardId = 'cardId';

    const string getBonusId = 'bonusId';

    const string getInvoiceNo = 'invoiceNo';

    const string getExtensionCode = 'extensionCode';

    const string getExtensionId = 'extensionId';

    const string getToBuyer = 'toBuyer';

    const string getPayNote = 'payNote';

    const string getAgencyId = 'agencyId';

    const string getInvType = 'invType';

    const string getTax = 'tax';

    const string getIsSeparate = 'isSeparate';

    const string getParentId = 'parentId';

    const string getDiscount = 'discount';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getOrderId => 'required',
            self::getOrderSn => 'required',
            self::getUserId => 'required',
            self::getOrderStatus => 'required',
            self::getShippingStatus => 'required',
            self::getPayStatus => 'required',
            self::getConsignee => 'required',
            self::getCountry => 'required',
            self::getProvince => 'required',
            self::getCity => 'required',
            self::getDistrict => 'required',
            self::getAddress => 'required',
            self::getZipcode => 'required',
            self::getTel => 'required',
            self::getMobile => 'required',
            self::getEmail => 'required',
            self::getBestTime => 'required',
            self::getSignBuilding => 'required',
            self::getPostscript => 'required',
            self::getShippingId => 'required',
            self::getShippingName => 'required',
            self::getPayId => 'required',
            self::getPayName => 'required',
            self::getHowOos => 'required',
            self::getHowSurplus => 'required',
            self::getPackName => 'required',
            self::getCardName => 'required',
            self::getCardMessage => 'required',
            self::getInvPayee => 'required',
            self::getInvContent => 'required',
            self::getGoodsAmount => 'required',
            self::getShippingFee => 'required',
            self::getInsureFee => 'required',
            self::getPayFee => 'required',
            self::getPackFee => 'required',
            self::getCardFee => 'required',
            self::getMoneyPaid => 'required',
            self::getSurplus => 'required',
            self::getIntegral => 'required',
            self::getIntegralMoney => 'required',
            self::getBonus => 'required',
            self::getOrderAmount => 'required',
            self::getFromAd => 'required',
            self::getReferer => 'required',
            self::getAddTime => 'required',
            self::getConfirmTime => 'required',
            self::getPayTime => 'required',
            self::getShippingTime => 'required',
            self::getPackId => 'required',
            self::getCardId => 'required',
            self::getBonusId => 'required',
            self::getInvoiceNo => 'required',
            self::getExtensionCode => 'required',
            self::getExtensionId => 'required',
            self::getToBuyer => 'required',
            self::getPayNote => 'required',
            self::getAgencyId => 'required',
            self::getInvType => 'required',
            self::getTax => 'required',
            self::getIsSeparate => 'required',
            self::getParentId => 'required',
            self::getDiscount => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请设置',
            self::getOrderSn.'.required' => '请设置订单编号',
            self::getUserId.'.required' => '请设置用户ID',
            self::getOrderStatus.'.required' => '请设置订单状态',
            self::getShippingStatus.'.required' => '请设置配送状态',
            self::getPayStatus.'.required' => '请设置支付状态',
            self::getConsignee.'.required' => '请设置收货人',
            self::getCountry.'.required' => '请设置国家',
            self::getProvince.'.required' => '请设置省份',
            self::getCity.'.required' => '请设置城市',
            self::getDistrict.'.required' => '请设置区县',
            self::getAddress.'.required' => '请设置详细地址',
            self::getZipcode.'.required' => '请设置邮政编码',
            self::getTel.'.required' => '请设置电话',
            self::getMobile.'.required' => '请设置手机',
            self::getEmail.'.required' => '请设置邮箱',
            self::getBestTime.'.required' => '请设置最佳送货时间',
            self::getSignBuilding.'.required' => '请设置标志建筑',
            self::getPostscript.'.required' => '请设置订单附言',
            self::getShippingId.'.required' => '请设置配送方式ID',
            self::getShippingName.'.required' => '请设置配送方式名称',
            self::getPayId.'.required' => '请设置支付方式ID',
            self::getPayName.'.required' => '请设置支付方式名称',
            self::getHowOos.'.required' => '请设置缺货处理方式',
            self::getHowSurplus.'.required' => '请设置余额处理方式',
            self::getPackName.'.required' => '请设置包装名称',
            self::getCardName.'.required' => '请设置贺卡名称',
            self::getCardMessage.'.required' => '请设置贺卡内容',
            self::getInvPayee.'.required' => '请设置发票抬头',
            self::getInvContent.'.required' => '请设置发票内容',
            self::getGoodsAmount.'.required' => '请设置商品总金额',
            self::getShippingFee.'.required' => '请设置配送费用',
            self::getInsureFee.'.required' => '请设置保价费用',
            self::getPayFee.'.required' => '请设置支付费用',
            self::getPackFee.'.required' => '请设置包装费用',
            self::getCardFee.'.required' => '请设置贺卡费用',
            self::getMoneyPaid.'.required' => '请设置已付款金额',
            self::getSurplus.'.required' => '请设置余额',
            self::getIntegral.'.required' => '请设置使用积分',
            self::getIntegralMoney.'.required' => '请设置积分抵扣金额',
            self::getBonus.'.required' => '请设置红包金额',
            self::getOrderAmount.'.required' => '请设置订单总金额',
            self::getFromAd.'.required' => '请设置来源广告ID',
            self::getReferer.'.required' => '请设置来源页面',
            self::getAddTime.'.required' => '请设置添加时间',
            self::getConfirmTime.'.required' => '请设置确认时间',
            self::getPayTime.'.required' => '请设置支付时间',
            self::getShippingTime.'.required' => '请设置配送时间',
            self::getPackId.'.required' => '请设置包装ID',
            self::getCardId.'.required' => '请设置贺卡ID',
            self::getBonusId.'.required' => '请设置红包ID',
            self::getInvoiceNo.'.required' => '请设置发货单号',
            self::getExtensionCode.'.required' => '请设置扩展代码',
            self::getExtensionId.'.required' => '请设置扩展ID',
            self::getToBuyer.'.required' => '请设置给买家的留言',
            self::getPayNote.'.required' => '请设置付款备注',
            self::getAgencyId.'.required' => '请设置办事处ID',
            self::getInvType.'.required' => '请设置发票类型',
            self::getTax.'.required' => '请设置税额',
            self::getIsSeparate.'.required' => '请设置是否拆分',
            self::getParentId.'.required' => '请设置父订单ID',
            self::getDiscount.'.required' => '请设置折扣金额',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
