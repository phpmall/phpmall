<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsTypeAttribute;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsTypeAttributeQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getAttrId, description: '', type: 'integer'),
        new OA\Property(property: self::getCatId, description: '分类ID', type: 'integer'),
    ]
)]
class GoodsTypeAttributeQueryRequest extends FormRequest
{
    const string getAttrId = 'attrId';

    const string getCatId = 'catId';

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
