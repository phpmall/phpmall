<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getActId, description: '', type: 'integer'),
        new OA\Property(property: self::getActName, description: '活动名称', type: 'string'),
    ]
)]
class ActivityQueryRequest extends FormRequest
{
    const string getActId = 'actId';

    const string getActName = 'actName';

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
