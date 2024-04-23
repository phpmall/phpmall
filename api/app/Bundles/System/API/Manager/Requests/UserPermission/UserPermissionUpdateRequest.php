<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Requests\UserPermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserPermissionUpdateRequest',
    required: [
        'id',
        'user_id',
        'permission_id',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'permission_id', description: '权限资源ID', type: 'integer'),
    ]
)]
class UserPermissionUpdateRequest extends FormRequest
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
            'user_id' => 'require',
            'permission_id' => 'require',
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
            'user_id.require' => '请设置用户ID',
            'permission_id.require' => '请设置权限资源ID',
        ];
    }
}
