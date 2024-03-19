<?php

declare(strict_types=1);

namespace App\Http\Requests\UserLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserLogCreateRequest',
    required: [
        'id',
        'user_id',
        'event_type',
        'event_time',
        'event_details',
        'ip_address',
        'user_agent',
        'created_at',
        'updated_at',
        'deleted_at',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'event_type', description: '事件类型，用于区分不同的用户操作或系统事件', type: 'string'),
        new OA\Property(property: 'event_time', description: '事件发生的时间', type: 'string'),
        new OA\Property(property: 'event_details', description: '事件的详细信息，推荐json格式', type: 'string'),
        new OA\Property(property: 'ip_address', description: '用户的IP地址', type: 'string'),
        new OA\Property(property: 'user_agent', description: '用户代理字符串', type: 'string'),
        new OA\Property(property: 'created_at', description: '', type: 'string'),
        new OA\Property(property: 'updated_at', description: '', type: 'string'),
        new OA\Property(property: 'deleted_at', description: '', type: 'string'),
    ]
)]
class UserLogCreateRequest extends FormRequest
{
    protected array $rule = [
        'id' => 'require',
        'user_id' => 'require',
        'event_type' => 'require',
        'event_time' => 'require',
        'event_details' => 'require',
        'ip_address' => 'require',
        'user_agent' => 'require',
        'created_at' => 'require',
        'updated_at' => 'require',
        'deleted_at' => 'require',
    ];

    protected array $message = [
        'id.require' => '请设置ID',
        'user_id.require' => '请设置用户ID',
        'event_type.require' => '请设置事件类型，用于区分不同的用户操作或系统事件',
        'event_time.require' => '请设置事件发生的时间',
        'event_details.require' => '请设置事件的详细信息，推荐json格式',
        'ip_address.require' => '请设置用户的IP地址',
        'user_agent.require' => '请设置用户代理字符串',
        'created_at.require' => '请设置',
        'updated_at.require' => '请设置',
        'deleted_at.require' => '请设置',
    ];
}