<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsCatRecommend;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsCatRecommendQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRecommendType, description: '推荐类型', type: 'integer'),
    ]
)]
class GoodsCatRecommendQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getRecommendType = 'recommendType';

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
