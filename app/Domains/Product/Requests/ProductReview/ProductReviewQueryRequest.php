<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductReview;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductReviewQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderItemId, description: '订单商品项ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getMerchantId, description: '商家ID', type: 'integer'),
    ]
)]
class ProductReviewQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderItemId = 'orderItemId';

    public const string getProductId = 'productId';

    public const string getUserId = 'userId';

    public const string getMerchantId = 'merchantId';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
