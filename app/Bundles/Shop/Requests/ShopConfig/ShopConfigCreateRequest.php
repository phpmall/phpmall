<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopConfig;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopConfigCreateRequest',
    required: [
        self::getParentId,
        self::getCode,
        self::getType,
        self::getStoreRange,
        self::getStoreDir,
        self::getValue,
        self::getSortOrder,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getCode, description: '配置编码', type: 'string'),
        new OA\Property(property: self::getType, description: '配置类型', type: 'string'),
        new OA\Property(property: self::getStoreRange, description: '存储范围', type: 'string'),
        new OA\Property(property: self::getStoreDir, description: '存储目录', type: 'string'),
        new OA\Property(property: self::getValue, description: '配置值', type: 'string'),
        new OA\Property(property: self::getSortOrder, description: '排序顺序', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopConfigCreateRequest extends FormRequest
{
    const string getParentId = 'parentId';

    const string getCode = 'code';

    const string getType = 'type';

    const string getStoreRange = 'storeRange';

    const string getStoreDir = 'storeDir';

    const string getValue = 'value';

    const string getSortOrder = 'sortOrder';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getParentId => 'required',
            self::getCode => 'required',
            self::getType => 'required',
            self::getStoreRange => 'required',
            self::getStoreDir => 'required',
            self::getValue => 'required',
            self::getSortOrder => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getParentId.'.required' => '请设置父级ID',
            self::getCode.'.required' => '请设置配置编码',
            self::getType.'.required' => '请设置配置类型',
            self::getStoreRange.'.required' => '请设置存储范围',
            self::getStoreDir.'.required' => '请设置存储目录',
            self::getValue.'.required' => '请设置配置值',
            self::getSortOrder.'.required' => '请设置排序顺序',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
