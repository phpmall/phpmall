<?php

declare(strict_types=1);

namespace App\Bundles\Search\Requests\SearchEngine;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SearchEngineQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSearchEngine, description: '搜索引擎', type: 'string'),
    ]
)]
class SearchEngineQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getSearchEngine = 'searchEngine';

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
