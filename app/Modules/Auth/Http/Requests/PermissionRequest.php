<?php

namespace App\Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
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
        $permissionId = $this->route('permission');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('permissions', 'name')->ignore($permissionId),
            ],
            'display_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'min:0'],
            'type' => ['nullable', 'string', 'in:menu,button,api'],
            'route' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'integer', 'in:1,2'],
        ];
    }
}
