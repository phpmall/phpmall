<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsArticle;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsArticleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getArticleId, description: '文章ID', type: 'integer'),
    ]
)]
class GoodsArticleQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getArticleId = 'articleId';

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
