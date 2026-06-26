<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Security;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SecurityRealNameRequest',
    required: [
        self::getRealName,
        self::getIdNumber,
    ],
    properties: [
        new OA\Property(property: self::getRealName, description: '真实姓名', type: 'string'),
        new OA\Property(property: self::getIdNumber, description: '身份证号', type: 'string'),
    ]
)]
class SecurityRealNameRequest extends FormRequest
{
    const string getRealName = 'real_name';

    const string getIdNumber = 'id_number';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getRealName => ['required', 'string', 'max:50'],
            self::getIdNumber => ['required', 'string', 'regex:/^\d{17}[\dXx]$/'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getRealName.'.required' => '请填写真实姓名',
            self::getIdNumber.'.required' => '请填写身份证号',
            self::getIdNumber.'.regex' => '身份证号格式不正确',
        ];
    }
}
