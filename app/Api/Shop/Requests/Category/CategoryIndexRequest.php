<?php

declare(strict_types=1);

namespace App\Api\Shop\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopCategoryIndexRequest',
    properties: [
        new OA\Property(property: self::getParentId, description: '父分类ID', type: 'integer', nullable: true, example: 0),
        new OA\Property(property: self::getLevel, description: '分类层级', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class CategoryIndexRequest extends FormRequest
{
    const string getParentId = 'parent_id';

    const string getLevel = 'level';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getParentId => 'nullable|integer|min:0',
            self::getLevel => 'nullable|integer|min:1|max:3',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getParentId.'.integer' => '父分类ID必须是整数',
            self::getLevel.'.integer' => '分类层级必须是整数',
            self::getLevel.'.max' => '分类层级最大为3',
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
