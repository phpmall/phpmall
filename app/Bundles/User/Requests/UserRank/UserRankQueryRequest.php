<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserRank;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserRankQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getRankId, description: '', type: 'integer'),
    ]
)]
class UserRankQueryRequest extends FormRequest
{
    const string getRankId = 'rankId';

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
