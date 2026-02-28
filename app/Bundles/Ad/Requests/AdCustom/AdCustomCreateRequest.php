<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Requests\AdCustom;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdCustomCreateRequest',
    required: [
        self::getAdId,
        self::getAdType,
        self::getAdName,
        self::getAddTime,
        self::getContent,
        self::getUrl,
        self::getAdStatus,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getAdId, description: '', type: 'integer'),
        new OA\Property(property: self::getAdType, description: '广告类型', type: 'integer'),
        new OA\Property(property: self::getAdName, description: '广告名称', type: 'string'),
        new OA\Property(property: self::getAddTime, description: '添加时间', type: 'integer'),
        new OA\Property(property: self::getContent, description: '广告内容', type: 'string'),
        new OA\Property(property: self::getUrl, description: '广告链接', type: 'string'),
        new OA\Property(property: self::getAdStatus, description: '广告状态', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class AdCustomCreateRequest extends FormRequest
{
    const string getAdId = 'adId';

    const string getAdType = 'adType';

    const string getAdName = 'adName';

    const string getAddTime = 'addTime';

    const string getContent = 'content';

    const string getUrl = 'url';

    const string getAdStatus = 'adStatus';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getAdId => 'required',
            self::getAdType => 'required',
            self::getAdName => 'required',
            self::getAddTime => 'required',
            self::getContent => 'required',
            self::getUrl => 'required',
            self::getAdStatus => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getAdId.'.required' => '请设置',
            self::getAdType.'.required' => '请设置广告类型',
            self::getAdName.'.required' => '请设置广告名称',
            self::getAddTime.'.required' => '请设置添加时间',
            self::getContent.'.required' => '请设置广告内容',
            self::getUrl.'.required' => '请设置广告链接',
            self::getAdStatus.'.required' => '请设置广告状态',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
