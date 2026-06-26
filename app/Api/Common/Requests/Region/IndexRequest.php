<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Region;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonRegionIndexRequest',
    properties: [
        new OA\Property(property: self::getParentId, description: '父级地区ID', type: 'integer', nullable: true),
        new OA\Property(property: self::getLevel, description: '地区层级:1省,2市,3区', type: 'integer', nullable: true),
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string', nullable: true),
    ]
)]
class IndexRequest extends FormRequest
{
    const string getParentId = 'parent_id';

    const string getLevel = 'level';

    const string getKeyword = 'keyword';

    public function rules(): array
    {
        return [
            self::getParentId => ['nullable', 'integer', 'min:0'],
            self::getLevel => ['nullable', 'integer', 'in:1,2,3'],
            self::getKeyword => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getParentId.'.integer' => '父级地区ID必须为整数',
            self::getLevel.'.in' => '地区层级值不正确',
            self::getKeyword.'.max' => '关键词不能超过50个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
