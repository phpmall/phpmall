<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Requests\AdminLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminLogCreateRequest',
    required: [
        self::getLogId,
        self::getLogTime,
        self::getUserId,
        self::getLogInfo,
        self::getIpAddress,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getLogTime, description: '日志时间', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getLogInfo, description: '日志信息', type: 'string'),
        new OA\Property(property: self::getIpAddress, description: 'IP地址', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class AdminLogCreateRequest extends FormRequest
{
    const string getLogId = 'logId';

    const string getLogTime = 'logTime';

    const string getUserId = 'userId';

    const string getLogInfo = 'logInfo';

    const string getIpAddress = 'ipAddress';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getLogId => 'required',
            self::getLogTime => 'required',
            self::getUserId => 'required',
            self::getLogInfo => 'required',
            self::getIpAddress => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getLogId.'.required' => '请设置',
            self::getLogTime.'.required' => '请设置日志时间',
            self::getUserId.'.required' => '请设置用户ID',
            self::getLogInfo.'.required' => '请设置日志信息',
            self::getIpAddress.'.required' => '请设置IP地址',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
