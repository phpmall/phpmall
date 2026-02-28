<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityTopic;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityTopicQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getTopicId, description: '专题ID', type: 'integer'),
    ]
)]
class ActivityTopicQueryRequest extends FormRequest
{
    const string getTopicId = 'topicId';

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
