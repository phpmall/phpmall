<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderRefund;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderRefundUpdateRequest',
    required: [
        self::getId,
        self::getRefundNo,
        self::getOrderId,
        self::getOrderItemId,
        self::getUserId,
        self::getMerchantId,
        self::getType,
        self::getReason,
        self::getReasonType,
        self::getDescription,
        self::getImages,
        self::getApplyAmount,
        self::getRefundAmount,
        self::getStatus,
        self::getMerchantRemark,
        self::getPlatformRemark,
        self::getReturnExpressCompany,
        self::getReturnExpressNo,
        self::getReturnShipTime,
        self::getMerchantReceiptTime,
        self::getRefundTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRefundNo, description: '退款单号', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getOrderItemId, description: '订单商品项ID，可为空（整单退款）', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getMerchantId, description: '商家ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '退款类型：1-仅退款，2-退货退款，3-换货', type: 'integer'),
        new OA\Property(property: self::getReason, description: '退款原因', type: 'string'),
        new OA\Property(property: self::getReasonType, description: '原因分类', type: 'integer'),
        new OA\Property(property: self::getDescription, description: '补充说明', type: 'string'),
        new OA\Property(property: self::getImages, description: '凭证图片', type: 'string'),
        new OA\Property(property: self::getApplyAmount, description: '申请退款金额（分）', type: 'integer'),
        new OA\Property(property: self::getRefundAmount, description: '实际退款金额（分）', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '售后状态：0-待商家处理，1-商家同意，2-商家拒绝，3-退货中，4-平台介入，5-已退款，6-已拒绝，7-用户撤销', type: 'integer'),
        new OA\Property(property: self::getMerchantRemark, description: '商家处理备注', type: 'string'),
        new OA\Property(property: self::getPlatformRemark, description: '平台仲裁备注', type: 'string'),
        new OA\Property(property: self::getReturnExpressCompany, description: '退货快递公司', type: 'string'),
        new OA\Property(property: self::getReturnExpressNo, description: '退货快递单号', type: 'string'),
        new OA\Property(property: self::getReturnShipTime, description: '用户退货发货时间', type: 'string'),
        new OA\Property(property: self::getMerchantReceiptTime, description: '商家收到退货时间', type: 'string'),
        new OA\Property(property: self::getRefundTime, description: '实际退款时间', type: 'string'),
    ]
)]
class OrderRefundUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRefundNo = 'refundNo';

    public const string getOrderId = 'orderId';

    public const string getOrderItemId = 'orderItemId';

    public const string getUserId = 'userId';

    public const string getMerchantId = 'merchantId';

    public const string getType = 'type';

    public const string getReason = 'reason';

    public const string getReasonType = 'reasonType';

    public const string getDescription = 'description';

    public const string getImages = 'images';

    public const string getApplyAmount = 'applyAmount';

    public const string getRefundAmount = 'refundAmount';

    public const string getStatus = 'status';

    public const string getMerchantRemark = 'merchantRemark';

    public const string getPlatformRemark = 'platformRemark';

    public const string getReturnExpressCompany = 'returnExpressCompany';

    public const string getReturnExpressNo = 'returnExpressNo';

    public const string getReturnShipTime = 'returnShipTime';

    public const string getMerchantReceiptTime = 'merchantReceiptTime';

    public const string getRefundTime = 'refundTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getRefundNo => 'required',
            self::getOrderId => 'required',
            self::getOrderItemId => 'required',
            self::getUserId => 'required',
            self::getMerchantId => 'required',
            self::getType => 'required',
            self::getReason => 'required',
            self::getReasonType => 'required',
            self::getDescription => 'required',
            self::getImages => 'required',
            self::getApplyAmount => 'required',
            self::getRefundAmount => 'required',
            self::getStatus => 'required',
            self::getMerchantRemark => 'required',
            self::getPlatformRemark => 'required',
            self::getReturnExpressCompany => 'required',
            self::getReturnExpressNo => 'required',
            self::getReturnShipTime => 'required',
            self::getMerchantReceiptTime => 'required',
            self::getRefundTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getRefundNo.'.required' => '请设置退款单号',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getOrderItemId.'.required' => '请设置订单商品项ID，可为空（整单退款）',
            self::getUserId.'.required' => '请设置用户ID',
            self::getMerchantId.'.required' => '请设置商家ID',
            self::getType.'.required' => '请设置退款类型：1-仅退款，2-退货退款，3-换货',
            self::getReason.'.required' => '请设置退款原因',
            self::getReasonType.'.required' => '请设置原因分类',
            self::getDescription.'.required' => '请设置补充说明',
            self::getImages.'.required' => '请设置凭证图片',
            self::getApplyAmount.'.required' => '请设置申请退款金额（分）',
            self::getRefundAmount.'.required' => '请设置实际退款金额（分）',
            self::getStatus.'.required' => '请设置售后状态：0-待商家处理，1-商家同意，2-商家拒绝，3-退货中，4-平台介入，5-已退款，6-已拒绝，7-用户撤销',
            self::getMerchantRemark.'.required' => '请设置商家处理备注',
            self::getPlatformRemark.'.required' => '请设置平台仲裁备注',
            self::getReturnExpressCompany.'.required' => '请设置退货快递公司',
            self::getReturnExpressNo.'.required' => '请设置退货快递单号',
            self::getReturnShipTime.'.required' => '请设置用户退货发货时间',
            self::getMerchantReceiptTime.'.required' => '请设置商家收到退货时间',
            self::getRefundTime.'.required' => '请设置实际退款时间',
        ];
    }
}
