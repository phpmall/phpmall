<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsLinkGoods;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsLinkGoodsQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getLinkGoodsId, description: '关联商品ID', type: 'integer'),
    ]
)]
class GoodsLinkGoodsQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getLinkGoodsId = 'linkGoodsId';

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
