<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserBonus;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserBonusQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getBonusId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
    ]
)]
class UserBonusQueryRequest extends FormRequest
{
    const string getBonusId = 'bonusId';

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
