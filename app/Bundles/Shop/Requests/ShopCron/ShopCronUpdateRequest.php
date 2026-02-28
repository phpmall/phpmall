<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopCron;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopCronUpdateRequest',
    required: [
        self::getCronId,
        self::getCronCode,
        self::getCronName,
        self::getCronDesc,
        self::getCronOrder,
        self::getCronConfig,
        self::getThistime,
        self::getNextime,
        self::getDay,
        self::getWeek,
        self::getHour,
        self::getMinute,
        self::getEnable,
        self::getRunOnce,
        self::getAllowIp,
        self::getAlowFiles,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getCronId, description: '', type: 'integer'),
        new OA\Property(property: self::getCronCode, description: '计划任务代码', type: 'string'),
        new OA\Property(property: self::getCronName, description: '计划任务名称', type: 'string'),
        new OA\Property(property: self::getCronDesc, description: '计划任务描述', type: 'string'),
        new OA\Property(property: self::getCronOrder, description: '排序', type: 'integer'),
        new OA\Property(property: self::getCronConfig, description: '计划任务配置', type: 'string'),
        new OA\Property(property: self::getThistime, description: '本次执行时间', type: 'integer'),
        new OA\Property(property: self::getNextime, description: '下次执行时间', type: 'integer'),
        new OA\Property(property: self::getDay, description: '日', type: 'integer'),
        new OA\Property(property: self::getWeek, description: '周', type: 'string'),
        new OA\Property(property: self::getHour, description: '时', type: 'string'),
        new OA\Property(property: self::getMinute, description: '分', type: 'string'),
        new OA\Property(property: self::getEnable, description: '是否启用', type: 'integer'),
        new OA\Property(property: self::getRunOnce, description: '是否只运行一次', type: 'integer'),
        new OA\Property(property: self::getAllowIp, description: '允许的IP', type: 'string'),
        new OA\Property(property: self::getAlowFiles, description: '允许的文件', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopCronUpdateRequest extends FormRequest
{
    const string getCronId = 'cronId';

    const string getCronCode = 'cronCode';

    const string getCronName = 'cronName';

    const string getCronDesc = 'cronDesc';

    const string getCronOrder = 'cronOrder';

    const string getCronConfig = 'cronConfig';

    const string getThistime = 'thistime';

    const string getNextime = 'nextime';

    const string getDay = 'day';

    const string getWeek = 'week';

    const string getHour = 'hour';

    const string getMinute = 'minute';

    const string getEnable = 'enable';

    const string getRunOnce = 'runOnce';

    const string getAllowIp = 'allowIp';

    const string getAlowFiles = 'alowFiles';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getCronId => 'required',
            self::getCronCode => 'required',
            self::getCronName => 'required',
            self::getCronDesc => 'required',
            self::getCronOrder => 'required',
            self::getCronConfig => 'required',
            self::getThistime => 'required',
            self::getNextime => 'required',
            self::getDay => 'required',
            self::getWeek => 'required',
            self::getHour => 'required',
            self::getMinute => 'required',
            self::getEnable => 'required',
            self::getRunOnce => 'required',
            self::getAllowIp => 'required',
            self::getAlowFiles => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCronId.'.required' => '请设置',
            self::getCronCode.'.required' => '请设置计划任务代码',
            self::getCronName.'.required' => '请设置计划任务名称',
            self::getCronDesc.'.required' => '请设置计划任务描述',
            self::getCronOrder.'.required' => '请设置排序',
            self::getCronConfig.'.required' => '请设置计划任务配置',
            self::getThistime.'.required' => '请设置本次执行时间',
            self::getNextime.'.required' => '请设置下次执行时间',
            self::getDay.'.required' => '请设置日',
            self::getWeek.'.required' => '请设置周',
            self::getHour.'.required' => '请设置时',
            self::getMinute.'.required' => '请设置分',
            self::getEnable.'.required' => '请设置是否启用',
            self::getRunOnce.'.required' => '请设置是否只运行一次',
            self::getAllowIp.'.required' => '请设置允许的IP',
            self::getAlowFiles.'.required' => '请设置允许的文件',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
