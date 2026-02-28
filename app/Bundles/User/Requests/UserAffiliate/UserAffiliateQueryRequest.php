<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserAffiliate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAffiliateQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
    ]
)]
class UserAffiliateQueryRequest extends FormRequest
{
    const string getLogId = 'logId';

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
