<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PermissionUpdateRequest',
    required: [
        'id',
        'parent_id',
        'module',
        'icon',
        'name',
        'resource',
        'menu',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'parent_id', description: '父级ID', type: 'integer'),
        new OA\Property(property: 'module', description: '模块名:如manager,merchant', type: 'string'),
        new OA\Property(property: 'icon', description: '菜单图标', type: 'string'),
        new OA\Property(property: 'name', description: '资源名称', type: 'string'),
        new OA\Property(property: 'resource', description: '资源标识', type: 'string'),
        new OA\Property(property: 'menu', description: '是否为菜单项:1是,0否', type: 'integer'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer'),
    ]
)]
class PermissionUpdateRequest extends FormRequest
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
            'parent_id' => 'require',
            'module' => 'require',
            'icon' => 'require',
            'name' => 'require',
            'resource' => 'require',
            'menu' => 'require',
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
            'parent_id.require' => '请设置父级ID',
            'module.require' => '请设置模块名',
            'icon.require' => '请设置菜单图标',
            'name.require' => '请设置资源名称',
            'resource.require' => '请设置资源标识',
            'menu.require' => '请设置是否为菜单项',
            'sort.require' => '请设置排序',
            'status.require' => '请设置状态',
        ];
    }
}
