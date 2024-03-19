<?php

declare(strict_types=1);

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthenticationUpdateRequest',
    required: [
        'id',
        'user_id',
        'user_uuid',
        'type',
        'identifier',
        'credentials',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'user_uuid', description: '全局ID', type: 'string'),
        new OA\Property(property: 'type', description: '类型:wechat_open_id,wechat_union_id,ding_talk_open_id', type: 'string'),
        new OA\Property(property: 'identifier', description: '标识:如openid', type: 'string'),
        new OA\Property(property: 'credentials', description: '凭证:如密码,token', type: 'string'),
        new OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer'),
        new OA\Property(property: 'created_at', description: '', type: 'string'),
        new OA\Property(property: 'updated_at', description: '', type: 'string'),
        new OA\Property(property: 'deleted_at', description: '', type: 'string'),
    ]
)]
class AuthenticationUpdateRequest extends FormRequest
{
    protected array $rule = [
        'id' => 'require',
        'user_id' => 'require',
        'user_uuid' => 'require',
        'type' => 'require',
        'identifier' => 'require',
        'credentials' => 'require',
        'status' => 'require',
        'created_at' => 'require',
        'updated_at' => 'require',
        'deleted_at' => 'require',
    ];

    protected array $message = [
        'id.require' => '请设置ID',
        'user_id.require' => '请设置用户ID',
        'user_uuid.require' => '请设置全局ID',
        'type.require' => '请设置类型:wechat_open_id,wechat_union_id,ding_talk_open_id',
        'identifier.require' => '请设置标识:如openid',
        'credentials.require' => '请设置凭证:如密码,token',
        'status.require' => '请设置状态:1正常,2禁用',
        'created_at.require' => '请设置',
        'updated_at.require' => '请设置',
        'deleted_at.require' => '请设置',
    ];
}