<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserTag;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserTagQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getTagId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
    ]
)]
class UserTagQueryRequest extends FormRequest
{
    const string getTagId = 'tagId';

    const string getUserId = 'userId';

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
