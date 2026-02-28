<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserCollect;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserCollectQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getRecId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getIsAttention, description: '是否关注', type: 'integer'),
    ]
)]
class UserCollectQueryRequest extends FormRequest
{
    const string getRecId = 'recId';

    const string getUserId = 'userId';

    const string getGoodsId = 'goodsId';

    const string getIsAttention = 'isAttention';

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
