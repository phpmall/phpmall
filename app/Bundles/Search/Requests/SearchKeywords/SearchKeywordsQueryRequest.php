<?php

declare(strict_types=1);

namespace App\Bundles\Search\Requests\SearchKeywords;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SearchKeywordsQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getKeywords, description: '关键词', type: 'string'),
    ]
)]
class SearchKeywordsQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getKeywords = 'keywords';

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
