<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopAutoManage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopAutoManageCreateRequest',
    required: [
        self::getItemId,
        self::getType,
        self::getStarttime,
        self::getEndtime,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getItemId, description: '项目ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型', type: 'string'),
        new OA\Property(property: self::getStarttime, description: '开始时间', type: 'integer'),
        new OA\Property(property: self::getEndtime, description: '结束时间', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopAutoManageCreateRequest extends FormRequest
{
    const string getItemId = 'itemId';

    const string getType = 'type';

    const string getStarttime = 'starttime';

    const string getEndtime = 'endtime';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getItemId => 'required',
            self::getType => 'required',
            self::getStarttime => 'required',
            self::getEndtime => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getItemId.'.required' => '请设置项目ID',
            self::getType.'.required' => '请设置类型',
            self::getStarttime.'.required' => '请设置开始时间',
            self::getEndtime.'.required' => '请设置结束时间',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
