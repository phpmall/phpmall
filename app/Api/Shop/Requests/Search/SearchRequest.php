<?php

declare(strict_types=1);

namespace App\Api\Shop\Requests\Search;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SearchRequest',
    required: [

    ],
    properties: [

    ]
)]
class SearchRequest extends FormRequest
{
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
