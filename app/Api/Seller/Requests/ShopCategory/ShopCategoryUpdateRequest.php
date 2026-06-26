<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\ShopCategory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerShopCategoryUpdateRequest',
    required: [
        self::getName,
    ],
    properties: [
        new OA\Property(property: self::getParentId, description: '父分类ID:0为顶级', type: 'integer'),
        new OA\Property(property: self::getName, description: '分类名称', type: 'string', maxLength: 50),
        new OA\Property(property: self::getIcon, description: '分类图标', type: 'string', nullable: true),
        new OA\Property(property: self::getSort, description: '排序值', type: 'integer'),
        new OA\Property(property: self::getIsShow, description: '是否显示:0隐藏,1显示', type: 'integer'),
    ]
)]
class ShopCategoryUpdateRequest extends FormRequest
{
    const string getParentId = 'parent_id';

    const string getName = 'name';

    const string getIcon = 'icon';

    const string getSort = 'sort';

    const string getIsShow = 'is_show';

    public function rules(): array
    {
        return [
            self::getParentId => ['nullable', 'integer', 'min:0'],
            self::getName => ['required', 'string', 'max:50'],
            self::getIcon => ['nullable', 'string'],
            self::getSort => ['nullable', 'integer', 'min:0'],
            self::getIsShow => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写分类名称',
            self::getName.'.max' => '分类名称不能超过50个字符',
            self::getIsShow.'.in' => '显示状态格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
