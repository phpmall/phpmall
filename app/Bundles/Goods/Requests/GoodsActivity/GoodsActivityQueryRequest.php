<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsActivity;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsActivityQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getActId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
    ]
)]
class GoodsActivityQueryRequest extends FormRequest
{
    const string getActId = 'actId';

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
