<?php

namespace App\Api\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'contact_name' => ['required', 'string', 'max:100'],
            'contact_phone' => ['required', 'string', 'regex:/^1[3-9]\d{9}$/'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'detail' => ['required', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'is_default' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
