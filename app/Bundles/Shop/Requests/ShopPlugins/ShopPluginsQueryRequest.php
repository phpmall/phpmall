<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopPlugins;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopPluginsQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCode, description: '插件编码', type: 'string'),
    ]
)]
class ShopPluginsQueryRequest extends FormRequest
{
    const string getId = 'id';

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
