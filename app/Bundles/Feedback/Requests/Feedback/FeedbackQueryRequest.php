<?php

declare(strict_types=1);

namespace App\Bundles\Feedback\Requests\Feedback;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FeedbackQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getMsgId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
    ]
)]
class FeedbackQueryRequest extends FormRequest
{
    const string getMsgId = 'msgId';

    const string getUserId = 'userId';

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
