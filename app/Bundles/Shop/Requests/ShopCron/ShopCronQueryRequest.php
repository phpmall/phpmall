<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopCron;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopCronQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getCronId, description: '', type: 'integer'),
        new OA\Property(property: self::getCronCode, description: '计划任务代码', type: 'string'),
        new OA\Property(property: self::getNextime, description: '下次执行时间', type: 'integer'),
        new OA\Property(property: self::getEnable, description: '是否启用', type: 'integer'),
    ]
)]
class ShopCronQueryRequest extends FormRequest
{
    const string getCronId = 'cronId';

    const string getCronCode = 'cronCode';

    const string getNextime = 'nextime';

    const string getEnable = 'enable';

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
