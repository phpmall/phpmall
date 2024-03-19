<?php

declare(strict_types=1);

namespace App\Http\Requests\UserRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserRoleCreateRequest',
    required: [
        'id',
        'user_id',
        'role_id',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'role_id', description: '角色ID', type: 'integer'),
    ]
)]
class UserRoleCreateRequest extends FormRequest
{
    protected array $rule = [
        'id' => 'require',
        'user_id' => 'require',
        'role_id' => 'require',
    ];

    protected array $message = [
        'id.require' => '请设置ID',
        'user_id.require' => '请设置用户ID',
        'role_id.require' => '请设置角色ID',
    ];
}