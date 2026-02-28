<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserCart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserCartQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getRecId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getSessionId, description: 'SessionID', type: 'string'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
    ]
)]
class UserCartQueryRequest extends FormRequest
{
    const string getRecId = 'recId';

    const string getUserId = 'userId';

    const string getSessionId = 'sessionId';

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
