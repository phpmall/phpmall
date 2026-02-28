<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopNav;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopNavUpdateRequest',
    required: [
        self::getId,
        self::getType,
        self::getCtype,
        self::getCid,
        self::getName,
        self::getIfshow,
        self::getVieworder,
        self::getOpennew,
        self::getUrl,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型', type: 'string'),
        new OA\Property(property: self::getCtype, description: '类别类型', type: 'string'),
        new OA\Property(property: self::getCid, description: '类别ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '导航名称', type: 'string'),
        new OA\Property(property: self::getIfshow, description: '是否显示', type: 'integer'),
        new OA\Property(property: self::getVieworder, description: '显示顺序', type: 'integer'),
        new OA\Property(property: self::getOpennew, description: '是否新窗口打开', type: 'integer'),
        new OA\Property(property: self::getUrl, description: 'URL地址', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopNavUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getType = 'type';

    const string getCtype = 'ctype';

    const string getCid = 'cid';

    const string getName = 'name';

    const string getIfshow = 'ifshow';

    const string getVieworder = 'vieworder';

    const string getOpennew = 'opennew';

    const string getUrl = 'url';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getType => 'required',
            self::getCtype => 'required',
            self::getCid => 'required',
            self::getName => 'required',
            self::getIfshow => 'required',
            self::getVieworder => 'required',
            self::getOpennew => 'required',
            self::getUrl => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getType.'.required' => '请设置类型',
            self::getCtype.'.required' => '请设置类别类型',
            self::getCid.'.required' => '请设置类别ID',
            self::getName.'.required' => '请设置导航名称',
            self::getIfshow.'.required' => '请设置是否显示',
            self::getVieworder.'.required' => '请设置显示顺序',
            self::getOpennew.'.required' => '请设置是否新窗口打开',
            self::getUrl.'.required' => '请设置URL地址',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
