<?php

namespace App\Api\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateProfileRequest',
    properties: [
        new OA\Property(property: 'nickname', description: '用户昵称', type: 'string', nullable: true),
        new OA\Property(property: 'avatar', description: '用户头像 URL', type: 'string', nullable: true),
        new OA\Property(property: 'name', description: '用户姓名', type: 'string', nullable: true),
        new OA\Property(property: 'gender', description: '性别:0未知，1男，2女', type: 'integer', nullable: true),
        new OA\Property(property: 'birthday', description: '生日', type: 'string', format: 'date', nullable: true),
    ]
)]
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
            'gender' => ['nullable', 'integer', 'in:0,1,2'],
            'birthday' => ['nullable', 'string', 'date_format:Y-m-d'],
        ];
    }
}
