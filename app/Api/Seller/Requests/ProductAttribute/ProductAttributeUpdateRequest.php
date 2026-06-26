<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\ProductAttribute;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerProductAttributeUpdateRequest',
    required: [
        self::getName,
        self::getValues,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '属性名称', type: 'string', maxLength: 100),
        new OA\Property(
            property: self::getValues,
            description: '属性可选值列表',
            type: 'array',
            items: new OA\Items(type: 'string')
        ),
        new OA\Property(property: self::getSort, description: '排序值', type: 'integer', nullable: true),
    ]
)]
class ProductAttributeUpdateRequest extends FormRequest
{
    const string getName = 'name';

    const string getValues = 'values';

    const string getSort = 'sort';

    public function rules(): array
    {
        return [
            self::getName => 'required|string|max:100',
            self::getValues => 'required|array',
            self::getValues.'.*' => 'string',
            self::getSort => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写属性名称',
            self::getValues.'.required' => '请提供属性可选值',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
