<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderUpdateRequest',
    required: [
        self::getId,
        self::getOrderNo,
        self::getUserId,
        self::getMerchantId,
        self::getParentOrderId,
        self::getOrderType,
        self::getStatus,
        self::getPayStatus,
        self::getRefundStatus,
        self::getProductAmount,
        self::getDiscountAmount,
        self::getFreightAmount,
        self::getPayAmount,
        self::getPayMethod,
        self::getPayTime,
        self::getPayTransactionId,
        self::getShipTime,
        self::getReceiptTime,
        self::getCancelTime,
        self::getCancelReason,
        self::getAutoReceiptTime,
        self::getRemark,
        self::getSource,
        self::getSellerRemark,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderNo, description: '订单号', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getMerchantId, description: '商家ID', type: 'integer'),
        new OA\Property(property: self::getParentOrderId, description: '父订单ID（拆单）', type: 'integer'),
        new OA\Property(property: self::getOrderType, description: '1=普通 2=秒杀 3=拼团 4=分销', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '10=待付款 20=已支付 30=待发货 40=已发货 50=待收货 60=已收货 70=已完成 80=已取消 90=退款中 100=已退款', type: 'integer'),
        new OA\Property(property: self::getPayStatus, description: '0=未支付 20=已支付 30=部分退款 100=全额退款', type: 'integer'),
        new OA\Property(property: self::getRefundStatus, description: '0=无退款 10=退款申请中 20=退款中 30=已退款 40=拒绝退款', type: 'integer'),
        new OA\Property(property: self::getProductAmount, description: '商品总金额（分）', type: 'integer'),
        new OA\Property(property: self::getDiscountAmount, description: '优惠金额（分）', type: 'integer'),
        new OA\Property(property: self::getFreightAmount, description: '运费（分）', type: 'integer'),
        new OA\Property(property: self::getPayAmount, description: '实付金额（分）', type: 'integer'),
        new OA\Property(property: self::getPayMethod, description: '1=微信 2=支付宝 3=余额 4=银联', type: 'integer'),
        new OA\Property(property: self::getPayTime, description: '支付时间', type: 'string'),
        new OA\Property(property: self::getPayTransactionId, description: '第三方支付流水号', type: 'string'),
        new OA\Property(property: self::getShipTime, description: '发货时间', type: 'string'),
        new OA\Property(property: self::getReceiptTime, description: '确认收货时间', type: 'string'),
        new OA\Property(property: self::getCancelTime, description: '取消时间', type: 'string'),
        new OA\Property(property: self::getCancelReason, description: '取消原因', type: 'string'),
        new OA\Property(property: self::getAutoReceiptTime, description: '自动确认收货时间', type: 'string'),
        new OA\Property(property: self::getRemark, description: '用户备注', type: 'string'),
        new OA\Property(property: self::getSource, description: '来源 1=PC 2=H5 3=小程序 4=App', type: 'integer'),
        new OA\Property(property: self::getSellerRemark, description: '商家备注', type: 'string'),
    ]
)]
class OrderUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderNo = 'orderNo';

    public const string getUserId = 'userId';

    public const string getMerchantId = 'merchantId';

    public const string getParentOrderId = 'parentOrderId';

    public const string getOrderType = 'orderType';

    public const string getStatus = 'status';

    public const string getPayStatus = 'payStatus';

    public const string getRefundStatus = 'refundStatus';

    public const string getProductAmount = 'productAmount';

    public const string getDiscountAmount = 'discountAmount';

    public const string getFreightAmount = 'freightAmount';

    public const string getPayAmount = 'payAmount';

    public const string getPayMethod = 'payMethod';

    public const string getPayTime = 'payTime';

    public const string getPayTransactionId = 'payTransactionId';

    public const string getShipTime = 'shipTime';

    public const string getReceiptTime = 'receiptTime';

    public const string getCancelTime = 'cancelTime';

    public const string getCancelReason = 'cancelReason';

    public const string getAutoReceiptTime = 'autoReceiptTime';

    public const string getRemark = 'remark';

    public const string getSource = 'source';

    public const string getSellerRemark = 'sellerRemark';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getOrderNo => 'required',
            self::getUserId => 'required',
            self::getMerchantId => 'required',
            self::getParentOrderId => 'required',
            self::getOrderType => 'required',
            self::getStatus => 'required',
            self::getPayStatus => 'required',
            self::getRefundStatus => 'required',
            self::getProductAmount => 'required',
            self::getDiscountAmount => 'required',
            self::getFreightAmount => 'required',
            self::getPayAmount => 'required',
            self::getPayMethod => 'required',
            self::getPayTime => 'required',
            self::getPayTransactionId => 'required',
            self::getShipTime => 'required',
            self::getReceiptTime => 'required',
            self::getCancelTime => 'required',
            self::getCancelReason => 'required',
            self::getAutoReceiptTime => 'required',
            self::getRemark => 'required',
            self::getSource => 'required',
            self::getSellerRemark => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getOrderNo.'.required' => '请设置订单号',
            self::getUserId.'.required' => '请设置用户ID',
            self::getMerchantId.'.required' => '请设置商家ID',
            self::getParentOrderId.'.required' => '请设置父订单ID（拆单）',
            self::getOrderType.'.required' => '请设置1=普通 2=秒杀 3=拼团 4=分销',
            self::getStatus.'.required' => '请设置10=待付款 20=已支付 30=待发货 40=已发货 50=待收货 60=已收货 70=已完成 80=已取消 90=退款中 100=已退款',
            self::getPayStatus.'.required' => '请设置0=未支付 20=已支付 30=部分退款 100=全额退款',
            self::getRefundStatus.'.required' => '请设置0=无退款 10=退款申请中 20=退款中 30=已退款 40=拒绝退款',
            self::getProductAmount.'.required' => '请设置商品总金额（分）',
            self::getDiscountAmount.'.required' => '请设置优惠金额（分）',
            self::getFreightAmount.'.required' => '请设置运费（分）',
            self::getPayAmount.'.required' => '请设置实付金额（分）',
            self::getPayMethod.'.required' => '请设置1=微信 2=支付宝 3=余额 4=银联',
            self::getPayTime.'.required' => '请设置支付时间',
            self::getPayTransactionId.'.required' => '请设置第三方支付流水号',
            self::getShipTime.'.required' => '请设置发货时间',
            self::getReceiptTime.'.required' => '请设置确认收货时间',
            self::getCancelTime.'.required' => '请设置取消时间',
            self::getCancelReason.'.required' => '请设置取消原因',
            self::getAutoReceiptTime.'.required' => '请设置自动确认收货时间',
            self::getRemark.'.required' => '请设置用户备注',
            self::getSource.'.required' => '请设置来源 1=PC 2=H5 3=小程序 4=App',
            self::getSellerRemark.'.required' => '请设置商家备注',
        ];
    }
}
