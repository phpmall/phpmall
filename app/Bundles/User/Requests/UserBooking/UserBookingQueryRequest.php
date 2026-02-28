<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserBooking;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserBookingQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getRecId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
    ]
)]
class UserBookingQueryRequest extends FormRequest
{
    const string getRecId = 'recId';

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
