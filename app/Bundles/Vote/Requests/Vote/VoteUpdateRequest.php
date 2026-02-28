<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Requests\Vote;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'VoteUpdateRequest',
    required: [
        self::getVoteId,
        self::getVoteName,
        self::getStartTime,
        self::getEndTime,
        self::getCanMulti,
        self::getVoteCount,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getVoteId, description: '', type: 'integer'),
        new OA\Property(property: self::getVoteName, description: '投票名称', type: 'string'),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'integer'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'integer'),
        new OA\Property(property: self::getCanMulti, description: '是否多选', type: 'integer'),
        new OA\Property(property: self::getVoteCount, description: '投票次数', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class VoteUpdateRequest extends FormRequest
{
    const string getVoteId = 'voteId';

    const string getVoteName = 'voteName';

    const string getStartTime = 'startTime';

    const string getEndTime = 'endTime';

    const string getCanMulti = 'canMulti';

    const string getVoteCount = 'voteCount';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getVoteId => 'required',
            self::getVoteName => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getCanMulti => 'required',
            self::getVoteCount => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getVoteId.'.required' => '请设置',
            self::getVoteName.'.required' => '请设置投票名称',
            self::getStartTime.'.required' => '请设置开始时间',
            self::getEndTime.'.required' => '请设置结束时间',
            self::getCanMulti.'.required' => '请设置是否多选',
            self::getVoteCount.'.required' => '请设置投票次数',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
