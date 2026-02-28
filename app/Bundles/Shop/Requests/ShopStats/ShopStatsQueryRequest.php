<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopStats;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopStatsQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAccessTime, description: '访问时间', type: 'integer'),
    ]
)]
class ShopStatsQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getAccessTime = 'accessTime';

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
