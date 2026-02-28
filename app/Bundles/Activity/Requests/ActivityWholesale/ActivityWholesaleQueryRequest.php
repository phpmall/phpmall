<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityWholesale;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityWholesaleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getActId, description: '', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
    ]
)]
class ActivityWholesaleQueryRequest extends FormRequest
{
    const string getActId = 'actId';

    const string getGoodsId = 'goodsId';

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
