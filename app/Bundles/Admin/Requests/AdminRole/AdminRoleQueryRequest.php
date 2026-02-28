<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Requests\AdminRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminRoleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getRoleId, description: '', type: 'integer'),
        new OA\Property(property: self::getRoleName, description: '角色名称', type: 'string'),
    ]
)]
class AdminRoleQueryRequest extends FormRequest
{
    const string getRoleId = 'roleId';

    const string getRoleName = 'roleName';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
