<?php

declare(strict_types=1);

namespace App\Bundles\Search\Requests\SearchEngine;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SearchEngineCreateRequest',
    required: [
        self::getDate,
        self::getSearchEngine,
        self::getCount,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getDate, description: '日期', type: 'string'),
        new OA\Property(property: self::getSearchEngine, description: '搜索引擎', type: 'string'),
        new OA\Property(property: self::getCount, description: '次数', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class SearchEngineCreateRequest extends FormRequest
{
    const string getDate = 'date';

    const string getSearchEngine = 'searchEngine';

    const string getCount = 'count';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getDate => 'required',
            self::getSearchEngine => 'required',
            self::getCount => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getDate.'.required' => '请设置日期',
            self::getSearchEngine.'.required' => '请设置搜索引擎',
            self::getCount.'.required' => '请设置次数',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
