<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductCategory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductCategoryQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父分类ID，0=根', type: 'integer'),
        new OA\Property(property: self::getSortOrder, description: '排序', type: 'integer'),
    ]
)]
class ProductCategoryQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getParentId = 'parentId';

    public const string getSortOrder = 'sortOrder';

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
