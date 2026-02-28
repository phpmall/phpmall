<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Requests\AdminUser;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminUserQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getUserId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserName, description: '用户名', type: 'string'),
        new OA\Property(property: self::getAgencyId, description: '办事处ID', type: 'integer'),
    ]
)]
class AdminUserQueryRequest extends FormRequest
{
    const string getUserId = 'userId';

    const string getUserName = 'userName';

    const string getAgencyId = 'agencyId';

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
