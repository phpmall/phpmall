<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\SubAccount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerSubAccountPermissionRequest',
    required: [self::getPermissionIds],
    properties: [
        new OA\Property(property: self::getPermissionIds, description: '权限ID列表', type: 'array', items: new OA\Items(type: 'integer')),
    ]
)]
class SubAccountPermissionRequest extends FormRequest
{
    const string getPermissionIds = 'permission_ids';

    public function rules(): array
    {
        return [
            self::getPermissionIds => 'required|array',
            self::getPermissionIds.'.*' => [
                'integer',
                'min:1',
                Rule::exists('permissions', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            self::getPermissionIds.'.required' => '请选择权限',
            self::getPermissionIds.'.*.exists' => '权限不存在',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
