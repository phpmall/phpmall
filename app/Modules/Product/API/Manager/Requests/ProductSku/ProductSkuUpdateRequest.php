<?php

declare(strict_types=1);

namespace App\Modules\Product\API\Manager\Requests\ProductSku;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductSkuUpdateRequest',
    required: [
        'id',
        'name',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'name', description: '名称', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer'),
    ]
)]
class ProductSkuUpdateRequest extends FormRequest
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
            'id' => 'require',
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
            'id.require' => '请设置ID',
            'name.require' => '请设置名称',
            'sort.require' => '请设置排序',
            'status.require' => '请设置状态',
        ];
    }
}
