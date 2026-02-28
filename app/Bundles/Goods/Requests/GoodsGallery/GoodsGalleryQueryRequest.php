<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsGallery;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsGalleryQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getImgId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
    ]
)]
class GoodsGalleryQueryRequest extends FormRequest
{
    const string getImgId = 'imgId';

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
