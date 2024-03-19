<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserUpdateRequest',
    required: [
        'id',
        'uuid',
        'name',
        'avatar',
        'mobile',
        'mobile_verified_time',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'uuid', description: '唯一ID', type: 'string'),
        new OA\Property(property: 'name', description: '昵称', type: 'string'),
        new OA\Property(property: 'avatar', description: '头像', type: 'string'),
        new OA\Property(property: 'mobile', description: '手机号码', type: 'string'),
        new OA\Property(property: 'mobile_verified_time', description: '手机号验证时间', type: 'string'),
        new OA\Property(property: 'password', description: '登录密码', type: 'string'),
        new OA\Property(property: 'remember_token', description: '', type: 'string'),
        new OA\Property(property: 'created_at', description: '', type: 'string'),
        new OA\Property(property: 'updated_at', description: '', type: 'string'),
        new OA\Property(property: 'deleted_at', description: '', type: 'string'),
    ]
)]
class UserUpdateRequest extends FormRequest
{
    protected array $rule = [
        'id' => 'require',
        'uuid' => 'require',
        'name' => 'require',
        'avatar' => 'require',
        'mobile' => 'require',
        'mobile_verified_time' => 'require',
        'password' => 'require',
        'remember_token' => 'require',
        'created_at' => 'require',
        'updated_at' => 'require',
        'deleted_at' => 'require',
    ];

    protected array $message = [
        'id.require' => '请设置ID',
        'uuid.require' => '请设置唯一ID',
        'name.require' => '请设置昵称',
        'avatar.require' => '请设置头像',
        'mobile.require' => '请设置手机号码',
        'mobile_verified_time.require' => '请设置手机号验证时间',
        'password.require' => '请设置登录密码',
        'remember_token.require' => '请设置',
        'created_at.require' => '请设置',
        'updated_at.require' => '请设置',
        'deleted_at.require' => '请设置',
    ];
}