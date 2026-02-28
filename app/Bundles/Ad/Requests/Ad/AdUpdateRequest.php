<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdUpdateRequest',
    required: [
        self::getAdId,
        self::getPositionId,
        self::getMediaType,
        self::getAdName,
        self::getAdLink,
        self::getAdCode,
        self::getStartTime,
        self::getEndTime,
        self::getLinkMan,
        self::getLinkEmail,
        self::getLinkPhone,
        self::getClickCount,
        self::getEnabled,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getAdId, description: '', type: 'integer'),
        new OA\Property(property: self::getPositionId, description: '广告位置ID', type: 'integer'),
        new OA\Property(property: self::getMediaType, description: '媒体类型', type: 'integer'),
        new OA\Property(property: self::getAdName, description: '广告名称', type: 'string'),
        new OA\Property(property: self::getAdLink, description: '广告链接', type: 'string'),
        new OA\Property(property: self::getAdCode, description: '广告代码', type: 'string'),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'integer'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'integer'),
        new OA\Property(property: self::getLinkMan, description: '联系人', type: 'string'),
        new OA\Property(property: self::getLinkEmail, description: '联系邮箱', type: 'string'),
        new OA\Property(property: self::getLinkPhone, description: '联系电话', type: 'string'),
        new OA\Property(property: self::getClickCount, description: '点击次数', type: 'integer'),
        new OA\Property(property: self::getEnabled, description: '是否启用', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class AdUpdateRequest extends FormRequest
{
    const string getAdId = 'adId';

    const string getPositionId = 'positionId';

    const string getMediaType = 'mediaType';

    const string getAdName = 'adName';

    const string getAdLink = 'adLink';

    const string getAdCode = 'adCode';

    const string getStartTime = 'startTime';

    const string getEndTime = 'endTime';

    const string getLinkMan = 'linkMan';

    const string getLinkEmail = 'linkEmail';

    const string getLinkPhone = 'linkPhone';

    const string getClickCount = 'clickCount';

    const string getEnabled = 'enabled';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getAdId => 'required',
            self::getPositionId => 'required',
            self::getMediaType => 'required',
            self::getAdName => 'required',
            self::getAdLink => 'required',
            self::getAdCode => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getLinkMan => 'required',
            self::getLinkEmail => 'required',
            self::getLinkPhone => 'required',
            self::getClickCount => 'required',
            self::getEnabled => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getAdId.'.required' => '请设置',
            self::getPositionId.'.required' => '请设置广告位置ID',
            self::getMediaType.'.required' => '请设置媒体类型',
            self::getAdName.'.required' => '请设置广告名称',
            self::getAdLink.'.required' => '请设置广告链接',
            self::getAdCode.'.required' => '请设置广告代码',
            self::getStartTime.'.required' => '请设置开始时间',
            self::getEndTime.'.required' => '请设置结束时间',
            self::getLinkMan.'.required' => '请设置联系人',
            self::getLinkEmail.'.required' => '请设置联系邮箱',
            self::getLinkPhone.'.required' => '请设置联系电话',
            self::getClickCount.'.required' => '请设置点击次数',
            self::getEnabled.'.required' => '请设置是否启用',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
