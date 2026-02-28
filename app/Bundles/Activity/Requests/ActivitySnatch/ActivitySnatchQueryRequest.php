<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivitySnatch;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivitySnatchQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getSnatchId, description: '夺宝ID', type: 'integer'),
    ]
)]
class ActivitySnatchQueryRequest extends FormRequest
{
    const string getLogId = 'logId';

    const string getSnatchId = 'snatchId';

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
