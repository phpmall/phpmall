<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Search;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalSearchRequest',
    required: [
        self::getKeyword,
    ],
    properties: [
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string'),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
        new OA\Property(property: self::getSortBy, description: '排序字段', type: 'string', example: 'created_at'),
        new OA\Property(property: self::getSortDirection, description: '排序方向: asc 或 desc', type: 'string', example: 'desc'),
    ]
)]
class SearchRequest extends FormRequest
{
    const string getKeyword = 'keyword';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    const string getSortBy = 'sort_by';

    const string getSortDirection = 'sort_direction';

    public function rules(): array
    {
        return [
            self::getKeyword => 'required|string|max:255',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
            self::getSortBy => 'sometimes|string|max:64',
            self::getSortDirection => 'sometimes|string|in:asc,desc',
        ];
    }

    public function messages(): array
    {
        return [
            self::getKeyword.'.required' => '请输入搜索关键词',
            self::getKeyword.'.max' => '搜索关键词长度不能超过 255 个字符',
            self::getPage.'.integer' => '页码必须是整数',
            self::getPage.'.min' => '页码不能小于 1',
            self::getPerPage.'.integer' => '每页数量必须是整数',
            self::getPerPage.'.max' => '每页数量不能超过 100',
            self::getSortDirection.'.in' => '排序方向只能是 asc 或 desc',
        ];
    }
}
