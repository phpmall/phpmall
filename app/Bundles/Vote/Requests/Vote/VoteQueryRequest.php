<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Requests\Vote;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'VoteQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getVoteId, description: '', type: 'integer'),
    ]
)]
class VoteQueryRequest extends FormRequest
{
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
