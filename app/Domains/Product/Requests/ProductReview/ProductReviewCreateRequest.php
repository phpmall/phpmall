<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductReview;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductReviewCreateRequest',
    required: [
        self::getOrderId,
        self::getOrderItemId,
        self::getProductId,
        self::getSkuId,
        self::getUserId,
        self::getMerchantId,
        self::getRating,
        self::getContent,
        self::getImages,
        self::getIsAnonymous,
        self::getIsAppend,
        self::getParentId,
        self::getMerchantReply,
        self::getMerchantReplyAt,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getOrderItemId, description: '订单商品项ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getMerchantId, description: '商家ID', type: 'integer'),
        new OA\Property(property: self::getRating, description: '评分星级：1-一星，2-二星，3-三星，4-四星，5-五星', type: 'integer'),
        new OA\Property(property: self::getContent, description: '评价内容', type: 'string'),
        new OA\Property(property: self::getImages, description: '评价图片', type: 'string'),
        new OA\Property(property: self::getIsAnonymous, description: '是否匿名：0-否，1-是', type: 'integer'),
        new OA\Property(property: self::getIsAppend, description: '是否追评：0-否，1-是', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '追评时指向原评价', type: 'integer'),
        new OA\Property(property: self::getMerchantReply, description: '商家回复', type: 'string'),
        new OA\Property(property: self::getMerchantReplyAt, description: '', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态：0-隐藏，1-显示', type: 'integer'),
    ]
)]
class ProductReviewCreateRequest extends FormRequest
{
    public const string getOrderId = 'orderId';

    public const string getOrderItemId = 'orderItemId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getUserId = 'userId';

    public const string getMerchantId = 'merchantId';

    public const string getRating = 'rating';

    public const string getContent = 'content';

    public const string getImages = 'images';

    public const string getIsAnonymous = 'isAnonymous';

    public const string getIsAppend = 'isAppend';

    public const string getParentId = 'parentId';

    public const string getMerchantReply = 'merchantReply';

    public const string getMerchantReplyAt = 'merchantReplyAt';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getOrderId => 'required',
            self::getOrderItemId => 'required',
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getUserId => 'required',
            self::getMerchantId => 'required',
            self::getRating => 'required',
            self::getContent => 'required',
            self::getImages => 'required',
            self::getIsAnonymous => 'required',
            self::getIsAppend => 'required',
            self::getParentId => 'required',
            self::getMerchantReply => 'required',
            self::getMerchantReplyAt => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请设置订单ID',
            self::getOrderItemId.'.required' => '请设置订单商品项ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getMerchantId.'.required' => '请设置商家ID',
            self::getRating.'.required' => '请设置评分星级：1-一星，2-二星，3-三星，4-四星，5-五星',
            self::getContent.'.required' => '请设置评价内容',
            self::getImages.'.required' => '请设置评价图片',
            self::getIsAnonymous.'.required' => '请设置是否匿名：0-否，1-是',
            self::getIsAppend.'.required' => '请设置是否追评：0-否，1-是',
            self::getParentId.'.required' => '请设置追评时指向原评价',
            self::getMerchantReply.'.required' => '请设置商家回复',
            self::getMerchantReplyAt.'.required' => '请设置',
            self::getStatus.'.required' => '请设置状态：0-隐藏，1-显示',
        ];
    }
}
