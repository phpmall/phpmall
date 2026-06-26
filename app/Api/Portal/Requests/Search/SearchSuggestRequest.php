<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Search;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalSearchSuggestRequest',
    required: [self::getKeyword],
    properties: [
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string'),
        new OA\Property(property: self::getLimit, description: '返回数量限制', type: 'integer', example: 10),
    ]
)]
class SearchSuggestRequest extends FormRequest
{
    const string getKeyword = 'keyword';

    const string getLimit = 'limit';

    public function rules(): array
    {
        return [
            self::getKeyword => 'required|string|max:255',
            self::getLimit => 'sometimes|integer|min:1|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            self::getKeyword.'.required' => '请输入搜索关键词',
            self::getKeyword.'.max' => '搜索关键词长度不能超过255个字符',
            self::getLimit.'.integer' => '返回数量必须是整数',
            self::getLimit.'.max' => '返回数量不能超过20',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
