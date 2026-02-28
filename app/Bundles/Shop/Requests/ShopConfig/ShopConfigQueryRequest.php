<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopConfig;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopConfigQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getCode, description: '配置编码', type: 'string'),
    ]
)]
class ShopConfigQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getParentId = 'parentId';

    const string getCode = 'code';

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
