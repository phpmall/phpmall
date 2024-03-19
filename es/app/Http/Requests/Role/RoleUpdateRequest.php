<?php

declare(strict_types=1);

namespace App\Http\Requests\Role;

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
        'created_at',
        'updated_at',
        'deleted_at',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'name', description: '角色名称', type: 'string'),
        new OA\Property(property: 'code', description: '角色代码', type: 'string'),
        new OA\Property(property: 'description', description: '角色描述', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer'),
        new OA\Property(property: 'created_at', description: '', type: 'string'),
        new OA\Property(property: 'updated_at', description: '', type: 'string'),
        new OA\Property(property: 'deleted_at', description: '', type: 'string'),
    ]
)]
class RoleUpdateRequest extends FormRequest
{
    protected array $rule = [
        'id' => 'require',
        'name' => 'require',
        'code' => 'require',
        'description' => 'require',
        'sort' => 'require',
        'status' => 'require',
        'created_at' => 'require',
        'updated_at' => 'require',
        'deleted_at' => 'require',
    ];

    protected array $message = [
        'id.require' => '请设置ID',
        'name.require' => '请设置角色名称',
        'code.require' => '请设置角色代码',
        'description.require' => '请设置角色描述',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:1正常,2禁用',
        'created_at.require' => '请设置',
        'updated_at.require' => '请设置',
        'deleted_at.require' => '请设置',
    ];
}