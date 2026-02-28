<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserAddress;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAddressQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getAddressId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
    ]
)]
class UserAddressQueryRequest extends FormRequest
{
    const string getAddressId = 'addressId';

    const string getUserId = 'userId';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
