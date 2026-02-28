<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopFriendLink;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopFriendLinkQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getLinkId, description: '', type: 'integer'),
        new OA\Property(property: self::getShowOrder, description: '排序', type: 'integer'),
    ]
)]
class ShopFriendLinkQueryRequest extends FormRequest
{
    const string getLinkId = 'linkId';

    const string getShowOrder = 'showOrder';

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
