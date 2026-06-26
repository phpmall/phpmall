<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Marketing;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalMarketingIndexRequest',
    properties: [
        new OA\Property(property: self::getType, description: '活动类型', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: self::getStatus, description: '活动状态:0未开始,1进行中,2已结束', type: 'integer', nullable: true),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class MarketingIndexRequest extends FormRequest
{
    const string getType = 'type';

    const string getStatus = 'status';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getType => 'nullable|integer|min:0',
            self::getStatus => 'nullable|integer|in:0,1,2',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getStatus.'.in' => '活动状态只能是0,1,2',
            self::getPage.'.integer' => '页码必须是整数',
            self::getPage.'.min' => '页码不能小于1',
            self::getPerPage.'.integer' => '每页数量必须是整数',
            self::getPerPage.'.max' => '每页数量不能超过100',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
