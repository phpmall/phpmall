<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderItemCreateRequest',
    required: [
        self::getOrderId,
        self::getProductId,
        self::getSkuId,
        self::getMerchantId,
        self::getProductTitle,
        self::getProductImage,
        self::getSkuSpecs,
        self::getPrice,
        self::getQuantity,
        self::getTotalAmount,
        self::getDiscountAmount,
        self::getRefundAmount,
        self::getRefundStatus,
        self::getIsCommented,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID', type: 'integer'),
        new OA\Property(property: self::getMerchantId, description: '商家ID', type: 'integer'),
        new OA\Property(property: self::getProductTitle, description: '商品标题（快照）', type: 'string'),
        new OA\Property(property: self::getProductImage, description: '商品主图（快照）', type: 'string'),
        new OA\Property(property: self::getSkuSpecs, description: '规格快照', type: 'string'),
        new OA\Property(property: self::getPrice, description: '下单时单价（分）', type: 'integer'),
        new OA\Property(property: self::getQuantity, description: '数量', type: 'integer'),
        new OA\Property(property: self::getTotalAmount, description: '小计（分）', type: 'integer'),
        new OA\Property(property: self::getDiscountAmount, description: '优惠分摊（分）', type: 'integer'),
        new OA\Property(property: self::getRefundAmount, description: '已退款金额（分）', type: 'integer'),
        new OA\Property(property: self::getRefundStatus, description: '售后状态：0-无售后，1-申请中，2-已退款，3-已拒绝', type: 'integer'),
        new OA\Property(property: self::getIsCommented, description: '是否评价：0-未评价，1-已评价', type: 'integer'),
    ]
)]
class OrderItemCreateRequest extends FormRequest
{
    public const string getOrderId = 'orderId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getMerchantId = 'merchantId';

    public const string getProductTitle = 'productTitle';

    public const string getProductImage = 'productImage';

    public const string getSkuSpecs = 'skuSpecs';

    public const string getPrice = 'price';

    public const string getQuantity = 'quantity';

    public const string getTotalAmount = 'totalAmount';

    public const string getDiscountAmount = 'discountAmount';

    public const string getRefundAmount = 'refundAmount';

    public const string getRefundStatus = 'refundStatus';

    public const string getIsCommented = 'isCommented';

    public function rules(): array
    {
        return [
            self::getOrderId => 'required',
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getMerchantId => 'required',
            self::getProductTitle => 'required',
            self::getProductImage => 'required',
            self::getSkuSpecs => 'required',
            self::getPrice => 'required',
            self::getQuantity => 'required',
            self::getTotalAmount => 'required',
            self::getDiscountAmount => 'required',
            self::getRefundAmount => 'required',
            self::getRefundStatus => 'required',
            self::getIsCommented => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请设置订单ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID',
            self::getMerchantId.'.required' => '请设置商家ID',
            self::getProductTitle.'.required' => '请设置商品标题（快照）',
            self::getProductImage.'.required' => '请设置商品主图（快照）',
            self::getSkuSpecs.'.required' => '请设置规格快照',
            self::getPrice.'.required' => '请设置下单时单价（分）',
            self::getQuantity.'.required' => '请设置数量',
            self::getTotalAmount.'.required' => '请设置小计（分）',
            self::getDiscountAmount.'.required' => '请设置优惠分摊（分）',
            self::getRefundAmount.'.required' => '请设置已退款金额（分）',
            self::getRefundStatus.'.required' => '请设置售后状态：0-无售后，1-申请中，2-已退款，3-已拒绝',
            self::getIsCommented.'.required' => '请设置是否评价：0-未评价，1-已评价',
        ];
    }
}
