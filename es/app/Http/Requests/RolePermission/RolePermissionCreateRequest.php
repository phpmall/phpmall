<?php

declare(strict_types=1);

namespace App\Http\Requests\RolePermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RolePermissionCreateRequest',
    required: [
        'id',
        'role_id',
        'permission_id',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'role_id', description: '角色ID', type: 'integer'),
        new OA\Property(property: 'permission_id', description: '权限资源ID', type: 'integer'),
    ]
)]
class RolePermissionCreateRequest extends FormRequest
{
    protected array $rule = [
        'id' => 'require',
        'role_id' => 'require',
        'permission_id' => 'require',
    ];

    protected array $message = [
        'id.require' => '请设置ID',
        'role_id.require' => '请设置角色ID',
        'permission_id.require' => '请设置权限资源ID',
    ];
}