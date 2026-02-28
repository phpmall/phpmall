<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityAuction;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityAuctionUpdateRequest',
    required: [
        self::getLogId,
        self::getActId,
        self::getBidUser,
        self::getBidPrice,
        self::getBidTime,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getActId, description: '活动ID', type: 'integer'),
        new OA\Property(property: self::getBidUser, description: '竞价用户', type: 'integer'),
        new OA\Property(property: self::getBidPrice, description: '竞价金额', type: 'string'),
        new OA\Property(property: self::getBidTime, description: '竞价时间', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ActivityAuctionUpdateRequest extends FormRequest
{
    const string getLogId = 'logId';

    const string getActId = 'actId';

    const string getBidUser = 'bidUser';

    const string getBidPrice = 'bidPrice';

    const string getBidTime = 'bidTime';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getLogId => 'required',
            self::getActId => 'required',
            self::getBidUser => 'required',
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
            self::getActId.'.required' => '请设置活动ID',
            self::getBidUser.'.required' => '请设置竞价用户',
            self::getBidPrice.'.required' => '请设置竞价金额',
            self::getBidTime.'.required' => '请设置竞价时间',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
