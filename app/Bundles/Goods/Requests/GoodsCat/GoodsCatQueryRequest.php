<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsCat;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsCatQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCatId, description: '分类ID', type: 'integer'),
    ]
)]
class GoodsCatQueryRequest extends FormRequest
{
    const string getId = 'id';

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
