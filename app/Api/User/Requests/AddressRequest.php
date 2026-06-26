<?php

namespace App\Api\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AddressRequest',
    required: [
        'contact_name',
        'contact_phone',
        'province',
        'city',
        'district',
        'detail',
    ],
    properties: [
        new OA\Property(property: 'contact_name', description: '联系人姓名', type: 'string'),
        new OA\Property(property: 'contact_phone', description: '联系人手机号', type: 'string'),
        new OA\Property(property: 'province', description: '省份', type: 'string'),
        new OA\Property(property: 'city', description: '城市', type: 'string'),
        new OA\Property(property: 'district', description: '区县', type: 'string'),
        new OA\Property(property: 'detail', description: '详细地址', type: 'string'),
        new OA\Property(property: 'zip_code', description: '邮编', type: 'string', nullable: true),
        new OA\Property(property: 'is_default', description: '是否默认:0否，1是', type: 'integer', nullable: true),
    ]
)]
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
