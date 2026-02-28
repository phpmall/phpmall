<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsBrand;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsBrandQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getBrandId, description: '', type: 'integer'),
        new OA\Property(property: self::getIsShow, description: '是否显示', type: 'integer'),
    ]
)]
class GoodsBrandQueryRequest extends FormRequest
{
    const string getBrandId = 'brandId';

    const string getIsShow = 'isShow';

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
