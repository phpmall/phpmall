<?php

declare(strict_types=1);

namespace App\Api\Shop\Requests\Search;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopSearchProductsRequest',
    required: [self::getKeyword],
    properties: [
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string'),
        new OA\Property(property: self::getCategoryId, description: '分类ID', type: 'integer', nullable: true),
        new OA\Property(property: self::getMinPrice, description: '最低价格(分)', type: 'integer', nullable: true),
        new OA\Property(property: self::getMaxPrice, description: '最高价格(分)', type: 'integer', nullable: true),
        new OA\Property(property: self::getSortBy, description: '排序字段', type: 'string', example: 'created_at'),
        new OA\Property(property: self::getSortDirection, description: '排序方向: asc 或 desc', type: 'string', example: 'desc'),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class SearchProductsRequest extends FormRequest
{
    const string getKeyword = 'keyword';

    const string getCategoryId = 'category_id';

    const string getMinPrice = 'min_price';

    const string getMaxPrice = 'max_price';

    const string getSortBy = 'sort_by';

    const string getSortDirection = 'sort_direction';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getKeyword => 'required|string|max:255',
            self::getCategoryId => 'nullable|integer|min:1',
            self::getMinPrice => 'nullable|integer|min:0',
            self::getMaxPrice => 'nullable|integer|min:0',
            self::getSortBy => 'sometimes|string|max:64',
            self::getSortDirection => 'sometimes|string|in:asc,desc',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getKeyword.'.required' => '请输入搜索关键词',
            self::getKeyword.'.max' => '搜索关键词长度不能超过255个字符',
            self::getPage.'.integer' => '页码必须是整数',
            self::getPage.'.min' => '页码不能小于1',
            self::getPerPage.'.integer' => '每页数量必须是整数',
            self::getPerPage.'.max' => '每页数量不能超过100',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
