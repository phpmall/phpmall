<?php

declare(strict_types=1);

namespace App\Modules\User\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getEmail, description: '', type: 'string'),
    ]
)]
class UserQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getEmail = 'email';

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
