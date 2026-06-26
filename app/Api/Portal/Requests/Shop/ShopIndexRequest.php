<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalShopIndexRequest',
    properties: [
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string', nullable: true),
        new OA\Property(property: self::getCategoryId, description: '店铺分类ID', type: 'integer', nullable: true),
        new OA\Property(property: self::getSortBy, description: '排序字段', type: 'string', example: 'created_at'),
        new OA\Property(property: self::getSortDirection, description: '排序方向: asc 或 desc', type: 'string', example: 'desc'),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class ShopIndexRequest extends FormRequest
{
    const string getKeyword = 'keyword';

    const string getCategoryId = 'category_id';

    const string getSortBy = 'sort_by';

    const string getSortDirection = 'sort_direction';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getKeyword => 'nullable|string|max:255',
            self::getCategoryId => 'nullable|integer|min:1',
            self::getSortBy => 'sometimes|string|max:64',
            self::getSortDirection => 'sometimes|string|in:asc,desc',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getKeyword.'.max' => '搜索关键词长度不能超过255个字符',
            self::getCategoryId.'.integer' => '分类ID必须是整数',
            self::getSortDirection.'.in' => '排序方向只能是asc或desc',
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
