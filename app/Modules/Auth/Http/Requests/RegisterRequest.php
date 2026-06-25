<?php

namespace App\Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required_without:email', 'string', 'regex:/^1[3-9]\d{9}$/', 'unique:users,phone'],
            'email' => ['required_without:phone', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'name' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required_without' => '手机号或邮箱至少填写一项',
            'phone.regex' => '手机号格式不正确',
            'phone.unique' => '手机号已注册',
            'email.required_without' => '手机号或邮箱至少填写一项',
            'email.unique' => '邮箱已注册',
            'password.min' => '密码长度不能少于8位',
            'password.confirmed' => '两次密码输入不一致',
        ];
    }
}
