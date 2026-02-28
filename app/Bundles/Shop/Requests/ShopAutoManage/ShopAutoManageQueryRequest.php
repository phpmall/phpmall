<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopAutoManage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopAutoManageQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型', type: 'string'),
    ]
)]
class ShopAutoManageQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getType = 'type';

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
