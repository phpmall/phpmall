<?php

namespace App\Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'nickname' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'name' => ['nullable', 'string', 'max:100'],
        ];
    }
}
