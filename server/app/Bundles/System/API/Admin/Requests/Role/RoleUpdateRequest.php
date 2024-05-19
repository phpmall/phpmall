<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RoleUpdateRequest',
    required: [
        'id',
        'name',
        'code',
        'description',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'name', description: '角色名称', type: 'string'),
        new OA\Property(property: 'code', description: '角色代码', type: 'string'),
        new OA\Property(property: 'description', description: '角色描述', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer'),
    ]
)]
class RoleUpdateRequest extends FormRequest
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
            'code' => 'require',
            'description' => 'require',
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
            'name.require' => '请设置角色名称',
            'code.require' => '请设置角色代码',
            'description.require' => '请设置角色描述',
            'sort.require' => '请设置排序',
            'status.require' => '请设置状态',
        ];
    }
}
