<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserAccountLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAccountLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
    ]
)]
class UserAccountLogQueryRequest extends FormRequest
{
    const string getLogId = 'logId';

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
