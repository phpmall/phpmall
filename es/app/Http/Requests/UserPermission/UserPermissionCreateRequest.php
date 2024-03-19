<?php

declare(strict_types=1);

namespace App\Http\Requests\UserPermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserPermissionCreateRequest',
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
class UserPermissionCreateRequest extends FormRequest
{
    protected array $rule = [
        'id' => 'require',
        'user_id' => 'require',
        'permission_id' => 'require',
    ];

    protected array $message = [
        'id.require' => '请设置ID',
        'user_id.require' => '请设置用户ID',
        'permission_id.require' => '请设置权限资源ID',
    ];
}