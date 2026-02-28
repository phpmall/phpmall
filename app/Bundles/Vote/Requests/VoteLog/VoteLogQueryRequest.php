<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Requests\VoteLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'VoteLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getVoteId, description: '投票ID', type: 'integer'),
    ]
)]
class VoteLogQueryRequest extends FormRequest
{
    const string getLogId = 'logId';

    const string getVoteId = 'voteId';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
