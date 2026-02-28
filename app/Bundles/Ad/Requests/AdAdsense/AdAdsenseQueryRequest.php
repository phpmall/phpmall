<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Requests\AdAdsense;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdAdsenseQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getFromAd, description: '广告ID', type: 'integer'),
    ]
)]
class AdAdsenseQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getFromAd = 'fromAd';

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
