<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Requests\User;

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
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'uuid', description: '全局ID', type: 'string'),
        new OA\Property(property: 'name', description: '昵称', type: 'string'),
        new OA\Property(property: 'avatar', description: '头像', type: 'string'),
        new OA\Property(property: 'mobile', description: '手机号码', type: 'string'),
        new OA\Property(property: 'mobile_verified_time', description: '手机号验证时间', type: 'string'),
        new OA\Property(property: 'password', description: '登录密码', type: 'string'),
        new OA\Property(property: 'remember_token', description: '', type: 'string'),
    ]
)]
class UserUpdateRequest extends FormRequest
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
            'uuid' => 'require',
            'name' => 'require',
            'avatar' => 'require',
            'mobile' => 'require',
            'mobile_verified_time' => 'require',
            'password' => 'require',
            'remember_token' => 'require',
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
            'uuid.require' => '请设置全局ID',
            'name.require' => '请设置昵称',
            'avatar.require' => '请设置头像',
            'mobile.require' => '请设置手机号码',
            'mobile_verified_time.require' => '请设置手机号验证时间',
            'password.require' => '请设置登录密码',
            'remember_token.require' => '请设置',
        ];
    }
}
