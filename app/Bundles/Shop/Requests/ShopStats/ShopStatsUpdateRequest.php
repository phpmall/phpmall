<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopStats;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopStatsUpdateRequest',
    required: [
        self::getId,
        self::getAccessTime,
        self::getIpAddress,
        self::getVisitTimes,
        self::getBrowser,
        self::getSystem,
        self::getLanguage,
        self::getArea,
        self::getRefererDomain,
        self::getRefererPath,
        self::getAccessUrl,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAccessTime, description: '访问时间', type: 'integer'),
        new OA\Property(property: self::getIpAddress, description: 'IP地址', type: 'string'),
        new OA\Property(property: self::getVisitTimes, description: '访问次数', type: 'integer'),
        new OA\Property(property: self::getBrowser, description: '浏览器', type: 'string'),
        new OA\Property(property: self::getSystem, description: '操作系统', type: 'string'),
        new OA\Property(property: self::getLanguage, description: '语言', type: 'string'),
        new OA\Property(property: self::getArea, description: '地区', type: 'string'),
        new OA\Property(property: self::getRefererDomain, description: '来源域名', type: 'string'),
        new OA\Property(property: self::getRefererPath, description: '来源路径', type: 'string'),
        new OA\Property(property: self::getAccessUrl, description: '访问URL', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopStatsUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getAccessTime = 'accessTime';

    const string getIpAddress = 'ipAddress';

    const string getVisitTimes = 'visitTimes';

    const string getBrowser = 'browser';

    const string getSystem = 'system';

    const string getLanguage = 'language';

    const string getArea = 'area';

    const string getRefererDomain = 'refererDomain';

    const string getRefererPath = 'refererPath';

    const string getAccessUrl = 'accessUrl';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getAccessTime => 'required',
            self::getIpAddress => 'required',
            self::getVisitTimes => 'required',
            self::getBrowser => 'required',
            self::getSystem => 'required',
            self::getLanguage => 'required',
            self::getArea => 'required',
            self::getRefererDomain => 'required',
            self::getRefererPath => 'required',
            self::getAccessUrl => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getAccessTime.'.required' => '请设置访问时间',
            self::getIpAddress.'.required' => '请设置IP地址',
            self::getVisitTimes.'.required' => '请设置访问次数',
            self::getBrowser.'.required' => '请设置浏览器',
            self::getSystem.'.required' => '请设置操作系统',
            self::getLanguage.'.required' => '请设置语言',
            self::getArea.'.required' => '请设置地区',
            self::getRefererDomain.'.required' => '请设置来源域名',
            self::getRefererPath.'.required' => '请设置来源路径',
            self::getAccessUrl.'.required' => '请设置访问URL',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
