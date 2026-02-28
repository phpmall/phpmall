<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Requests\GoodsVolumePrice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'GoodsVolumePriceQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getVolumeNumber, description: '数量', type: 'integer'),
    ]
)]
class GoodsVolumePriceQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getVolumeNumber = 'volumeNumber';

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
