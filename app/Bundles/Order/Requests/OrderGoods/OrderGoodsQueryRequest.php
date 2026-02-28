<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderGoods;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderGoodsQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getRecId, description: '', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
    ]
)]
class OrderGoodsQueryRequest extends FormRequest
{
    const string getRecId = 'recId';

    const string getOrderId = 'orderId';

    const string getGoodsId = 'goodsId';

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
