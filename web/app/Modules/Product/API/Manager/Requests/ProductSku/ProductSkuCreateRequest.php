<?php

declare(strict_types=1);

namespace App\Modules\Product\API\Manager\Requests\ProductSku;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductSkuCreateRequest',
    required: [
        'name',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'name', description: '名称', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer'),
    ]
)]
class ProductSkuCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'require',
            'sort' => 'require',
            'status' => 'require',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.require' => '请设置名称',
            'sort.require' => '请设置排序',
            'status.require' => '请设置状态',
        ];
    }
}
