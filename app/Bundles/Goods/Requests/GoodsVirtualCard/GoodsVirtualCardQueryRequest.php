<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsVirtualCard;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsVirtualCardQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getCardId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getCardSn, description: '卡号', type: 'string'),
        new OA\Property(property: self::getIsSaled, description: '是否已售', type: 'integer'),
    ]
)]
class GoodsVirtualCardQueryRequest extends FormRequest
{
    const string getCardId = 'cardId';

    const string getGoodsId = 'goodsId';

    const string getCardSn = 'cardSn';

    const string getIsSaled = 'isSaled';

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
