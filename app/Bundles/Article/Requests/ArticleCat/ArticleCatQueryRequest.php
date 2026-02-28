<?php

declare(strict_types=1);

namespace App\Bundles\Article\Requests\ArticleCat;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArticleCatQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getCatId, description: '', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getCatType, description: '分类类型', type: 'integer'),
        new OA\Property(property: self::getSortOrder, description: '排序', type: 'integer'),
    ]
)]
class ArticleCatQueryRequest extends FormRequest
{
    const string getCatId = 'catId';

    const string getParentId = 'parentId';

    const string getCatType = 'catType';

    const string getSortOrder = 'sortOrder';

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
