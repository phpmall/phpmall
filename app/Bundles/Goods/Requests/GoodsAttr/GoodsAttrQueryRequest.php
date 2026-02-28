<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsAttr;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsAttrQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getGoodsAttrId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getAttrId, description: '属性ID', type: 'integer'),
    ]
)]
class GoodsAttrQueryRequest extends FormRequest
{
    const string getGoodsAttrId = 'goodsAttrId';

    const string getGoodsId = 'goodsId';

    const string getAttrId = 'attrId';

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
