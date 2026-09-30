<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Goods;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsSearchRequest',
    title: '商品搜索请求入参',
    description: '前台商品列表检索过滤参数',
    properties: [
        new OA\Property(property: 'keyword', description: '搜索关键词', type: 'string', nullable: true, example: '手机'),
        new OA\Property(property: 'category_id', description: '分类ID', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: 'min_price', description: '最低价格（分）', type: 'integer', nullable: true, example: 1000),
        new OA\Property(property: 'max_price', description: '最高价格（分）', type: 'integer', nullable: true, example: 500000),
        new OA\Property(property: 'sort_by', description: '排序字段：price|sales|created_at|sort_order', type: 'string', nullable: true, example: 'sales'),
        new OA\Property(property: 'sort_order', description: '排序方向：asc|desc', type: 'string', nullable: true, example: 'desc'),
        new OA\Property(property: 'is_hot', description: '是否热销：0-否，1-是', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: 'is_new', description: '是否新品：0-否，1-是', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: 'is_recommend', description: '是否推荐：0-否，1-是', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: 'page', description: '页码', type: 'integer', default: 1, example: 1),
        new OA\Property(property: 'pageSize', description: '每页条数', type: 'integer', default: 20, example: 20),
    ]
)]
class GoodsSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer'],
            'min_price' => ['nullable', 'integer', 'min:0'],
            'max_price' => ['nullable', 'integer', 'min:0'],
            'sort_by' => ['nullable', 'string', 'in:price,sales,created_at,sort_order'],
            'sort_order' => ['nullable', 'string', 'in:asc,desc'],
            'is_hot' => ['nullable', 'integer', 'in:0,1'],
            'is_new' => ['nullable', 'integer', 'in:0,1'],
            'is_recommend' => ['nullable', 'integer', 'in:0,1'],
            'page' => ['nullable', 'integer', 'min:1'],
            'pageSize' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
