<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getUserId, description: '', type: 'integer'),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string'),
        new OA\Property(property: self::getUserName, description: '用户名', type: 'string'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getFlag, description: '标志', type: 'integer'),
    ]
)]
class UserQueryRequest extends FormRequest
{
    const string getUserId = 'userId';

    const string getEmail = 'email';

    const string getUserName = 'userName';

    const string getParentId = 'parentId';

    const string getFlag = 'flag';

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
