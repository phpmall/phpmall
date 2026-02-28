<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Requests\AdAdsense;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdAdsenseCreateRequest',
    required: [
        self::getFromAd,
        self::getReferer,
        self::getClicks,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getFromAd, description: '广告ID', type: 'integer'),
        new OA\Property(property: self::getReferer, description: '来源页面', type: 'string'),
        new OA\Property(property: self::getClicks, description: '点击次数', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class AdAdsenseCreateRequest extends FormRequest
{
    const string getFromAd = 'fromAd';

    const string getReferer = 'referer';

    const string getClicks = 'clicks';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getFromAd => 'required',
            self::getReferer => 'required',
            self::getClicks => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getFromAd.'.required' => '请设置广告ID',
            self::getReferer.'.required' => '请设置来源页面',
            self::getClicks.'.required' => '请设置点击次数',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
