<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Search;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalSearchHotKeywordsRequest',
    properties: [
        new OA\Property(property: self::getLimit, description: '返回数量限制', type: 'integer', example: 10),
    ]
)]
class SearchHotKeywordsRequest extends FormRequest
{
    const string getLimit = 'limit';

    public function rules(): array
    {
        return [
            self::getLimit => 'sometimes|integer|min:1|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            self::getLimit.'.integer' => '返回数量必须是整数',
            self::getLimit.'.max' => '返回数量不能超过50',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
