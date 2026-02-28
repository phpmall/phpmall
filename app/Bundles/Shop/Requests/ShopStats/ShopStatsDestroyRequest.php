<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopStats;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopStatsDestroyRequest',
    required: [
        'ids',
    ],
    properties: [
        new OA\Property(property: self::getIds, description: '数据ID集合', type: 'array', items: new OA\Items(type: 'integer')),
    ]
)]
class ShopStatsDestroyRequest extends FormRequest
{
    const string getIds = 'ids';

    public function rules(): array
    {
        return [
            self::getIds => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getIds.'.required' => '请设置删除数据ID',
        ];
    }
}
