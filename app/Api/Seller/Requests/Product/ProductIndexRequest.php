<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerProductIndexRequest',
    properties: [
        new OA\Property(property: self::getStatus, description: '商品状态', type: 'integer', nullable: true),
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string', nullable: true),
        new OA\Property(property: self::getCategoryId, description: '分类ID', type: 'integer', nullable: true),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class ProductIndexRequest extends FormRequest
{
    const string getStatus = 'status';

    const string getKeyword = 'keyword';

    const string getCategoryId = 'category_id';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getStatus => 'nullable|integer|min:0',
            self::getKeyword => 'nullable|string|max:255',
            self::getCategoryId => 'nullable|integer|min:1',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getStatus.'.integer' => '商品状态必须是整数',
            self::getStatus.'.min' => '商品状态不能小于0',
            self::getKeyword.'.string' => '搜索关键词必须是字符串',
            self::getKeyword.'.max' => '搜索关键词不能超过255个字符',
            self::getCategoryId.'.integer' => '分类ID必须是整数',
            self::getCategoryId.'.min' => '分类ID不能小于1',
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
