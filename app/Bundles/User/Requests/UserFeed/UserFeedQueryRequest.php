<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserFeed;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserFeedQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getFeedId, description: '', type: 'integer'),
    ]
)]
class UserFeedQueryRequest extends FormRequest
{
    const string getFeedId = 'feedId';

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
