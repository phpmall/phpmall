<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivitySnatch;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivitySnatchUpdateRequest',
    required: [
        self::getLogId,
        self::getSnatchId,
        self::getUserId,
        self::getBidPrice,
        self::getBidTime,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getSnatchId, description: '夺宝ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getBidPrice, description: '出价', type: 'string'),
        new OA\Property(property: self::getBidTime, description: '出价时间', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ActivitySnatchUpdateRequest extends FormRequest
{
    const string getLogId = 'logId';

    const string getSnatchId = 'snatchId';

    const string getUserId = 'userId';

    const string getBidPrice = 'bidPrice';

    const string getBidTime = 'bidTime';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getLogId => 'required',
            self::getSnatchId => 'required',
            self::getUserId => 'required',
            self::getBidPrice => 'required',
            self::getBidTime => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getLogId.'.required' => '请设置',
            self::getSnatchId.'.required' => '请设置夺宝ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getBidPrice.'.required' => '请设置出价',
            self::getBidTime.'.required' => '请设置出价时间',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
