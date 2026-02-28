<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityAuction;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityAuctionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getLogId, description: '', type: 'integer'),
        new OA\Property(property: self::getActId, description: '活动ID', type: 'integer'),
    ]
)]
class ActivityAuctionQueryRequest extends FormRequest
{
    const string getLogId = 'logId';

    const string getActId = 'actId';

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
