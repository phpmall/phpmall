<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Requests\AdPosition;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdPositionCreateRequest',
    required: [
        self::getPositionId,
        self::getPositionName,
        self::getAdWidth,
        self::getAdHeight,
        self::getPositionDesc,
        self::getPositionStyle,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getPositionId, description: '', type: 'integer'),
        new OA\Property(property: self::getPositionName, description: '广告位名称', type: 'string'),
        new OA\Property(property: self::getAdWidth, description: '广告宽度', type: 'integer'),
        new OA\Property(property: self::getAdHeight, description: '广告高度', type: 'integer'),
        new OA\Property(property: self::getPositionDesc, description: '广告位描述', type: 'string'),
        new OA\Property(property: self::getPositionStyle, description: '广告位样式', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class AdPositionCreateRequest extends FormRequest
{
    const string getPositionId = 'positionId';

    const string getPositionName = 'positionName';

    const string getAdWidth = 'adWidth';

    const string getAdHeight = 'adHeight';

    const string getPositionDesc = 'positionDesc';

    const string getPositionStyle = 'positionStyle';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getPositionId => 'required',
            self::getPositionName => 'required',
            self::getAdWidth => 'required',
            self::getAdHeight => 'required',
            self::getPositionDesc => 'required',
            self::getPositionStyle => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getPositionId.'.required' => '请设置',
            self::getPositionName.'.required' => '请设置广告位名称',
            self::getAdWidth.'.required' => '请设置广告宽度',
            self::getAdHeight.'.required' => '请设置广告高度',
            self::getPositionDesc.'.required' => '请设置广告位描述',
            self::getPositionStyle.'.required' => '请设置广告位样式',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
