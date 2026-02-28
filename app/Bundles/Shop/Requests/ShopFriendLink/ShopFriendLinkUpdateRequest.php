<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopFriendLink;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopFriendLinkUpdateRequest',
    required: [
        self::getLinkId,
        self::getLinkName,
        self::getLinkUrl,
        self::getLinkLogo,
        self::getShowOrder,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getLinkId, description: '', type: 'integer'),
        new OA\Property(property: self::getLinkName, description: '链接名称', type: 'string'),
        new OA\Property(property: self::getLinkUrl, description: '链接地址', type: 'string'),
        new OA\Property(property: self::getLinkLogo, description: '链接Logo', type: 'string'),
        new OA\Property(property: self::getShowOrder, description: '排序', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopFriendLinkUpdateRequest extends FormRequest
{
    const string getLinkId = 'linkId';

    const string getLinkName = 'linkName';

    const string getLinkUrl = 'linkUrl';

    const string getLinkLogo = 'linkLogo';

    const string getShowOrder = 'showOrder';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getLinkId => 'required',
            self::getLinkName => 'required',
            self::getLinkUrl => 'required',
            self::getLinkLogo => 'required',
            self::getShowOrder => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getLinkId.'.required' => '请设置',
            self::getLinkName.'.required' => '请设置链接名称',
            self::getLinkUrl.'.required' => '请设置链接地址',
            self::getLinkLogo.'.required' => '请设置链接Logo',
            self::getShowOrder.'.required' => '请设置排序',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
