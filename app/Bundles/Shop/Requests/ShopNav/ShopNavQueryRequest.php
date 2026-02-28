<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopNav;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopNavQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型', type: 'string'),
        new OA\Property(property: self::getIfshow, description: '是否显示', type: 'integer'),
    ]
)]
class ShopNavQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getType = 'type';

    const string getIfshow = 'ifshow';

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
