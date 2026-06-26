<?php

declare(strict_types=1);

namespace App\Api\Shop\Requests\Search;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopSearchFiltersRequest',
    required: [self::getKeyword],
    properties: [
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string'),
        new OA\Property(property: self::getCategoryId, description: '分类ID', type: 'integer', nullable: true),
    ]
)]
class SearchFiltersRequest extends FormRequest
{
    const string getKeyword = 'keyword';

    const string getCategoryId = 'category_id';

    public function rules(): array
    {
        return [
            self::getKeyword => 'required|string|max:255',
            self::getCategoryId => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            self::getKeyword.'.required' => '请输入搜索关键词',
            self::getKeyword.'.max' => '搜索关键词长度不能超过255个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
