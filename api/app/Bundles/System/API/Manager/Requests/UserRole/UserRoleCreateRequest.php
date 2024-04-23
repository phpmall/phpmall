<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Requests\UserRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserRoleCreateRequest',
    required: [
        'user_id',
        'role_id',
    ],
    properties: [
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'role_id', description: '角色ID', type: 'integer'),
    ]
)]
class UserRoleCreateRequest extends FormRequest
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
            'user_id' => 'require',
            'role_id' => 'require',
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
            'user_id.require' => '请设置用户ID',
            'role_id.require' => '请设置角色ID',
        ];
    }
}
