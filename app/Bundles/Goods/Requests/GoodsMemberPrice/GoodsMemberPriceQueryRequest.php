<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsMemberPrice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsMemberPriceQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getPriceId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserRank, description: '用户等级', type: 'integer'),
    ]
)]
class GoodsMemberPriceQueryRequest extends FormRequest
{
    const string getPriceId = 'priceId';

    const string getUserRank = 'userRank';

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
