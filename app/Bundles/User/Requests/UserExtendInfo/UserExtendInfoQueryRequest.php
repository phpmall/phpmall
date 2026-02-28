<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserExtendInfo;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserExtendInfoQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: '', type: 'integer'),
    ]
)]
class UserExtendInfoQueryRequest extends FormRequest
{
    const string getId = 'id';

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
