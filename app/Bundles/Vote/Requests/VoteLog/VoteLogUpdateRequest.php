<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Requests\VoteLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'VoteLogUpdateRequest',
    required: [
        self::getLogId,
        self::getVoteId,
        self::getIpAddress,
        self::getVoteTime,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getVoteId, description: '投票ID', type: 'integer'),
        new OA\Property(property: self::getIpAddress, description: 'IP地址', type: 'string'),
        new OA\Property(property: self::getVoteTime, description: '投票时间', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class VoteLogUpdateRequest extends FormRequest
{
    const string getLogId = 'logId';

    const string getVoteId = 'voteId';

    const string getIpAddress = 'ipAddress';

    const string getVoteTime = 'voteTime';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getLogId => 'required',
            self::getVoteId => 'required',
            self::getIpAddress => 'required',
            self::getVoteTime => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getLogId.'.required' => '请设置',
            self::getVoteId.'.required' => '请设置投票ID',
            self::getIpAddress.'.required' => '请设置IP地址',
            self::getVoteTime.'.required' => '请设置投票时间',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
