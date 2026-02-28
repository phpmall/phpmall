<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderBackGoods;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderBackGoodsQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getRecId, description: '', type: 'integer'),
        new OA\Property(property: self::getBackId, description: '退货单ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
    ]
)]
class OrderBackGoodsQueryRequest extends FormRequest
{
    const string getRecId = 'recId';

    const string getBackId = 'backId';

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
