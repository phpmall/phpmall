<?php

namespace App\Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'phone' => ['required_without:email', 'string', 'regex:/^1[3-9]\d{9}$/'],
            'email' => ['required_without:phone', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required_without' => '手机号或邮箱至少填写一项',
            'phone.regex' => '手机号格式不正确',
            'email.required_without' => '手机号或邮箱至少填写一项',
            'password.required' => '密码不能为空',
        ];
    }
}
