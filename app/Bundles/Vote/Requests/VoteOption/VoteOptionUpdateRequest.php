<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Requests\VoteOption;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'VoteOptionUpdateRequest',
    required: [
        self::getOptionId,
        self::getVoteId,
        self::getOptionName,
        self::getOptionCount,
        self::getOptionOrder,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getOptionId, description: '', type: 'integer'),
        new OA\Property(property: self::getVoteId, description: '投票ID', type: 'integer'),
        new OA\Property(property: self::getOptionName, description: '选项名称', type: 'string'),
        new OA\Property(property: self::getOptionCount, description: '选项票数', type: 'integer'),
        new OA\Property(property: self::getOptionOrder, description: '选项顺序', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class VoteOptionUpdateRequest extends FormRequest
{
    const string getOptionId = 'optionId';

    const string getVoteId = 'voteId';

    const string getOptionName = 'optionName';

    const string getOptionCount = 'optionCount';

    const string getOptionOrder = 'optionOrder';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getOptionId => 'required',
            self::getVoteId => 'required',
            self::getOptionName => 'required',
            self::getOptionCount => 'required',
            self::getOptionOrder => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOptionId.'.required' => '请设置',
            self::getVoteId.'.required' => '请设置投票ID',
            self::getOptionName.'.required' => '请设置选项名称',
            self::getOptionCount.'.required' => '请设置选项票数',
            self::getOptionOrder.'.required' => '请设置选项顺序',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
