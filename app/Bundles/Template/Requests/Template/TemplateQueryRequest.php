<?php

declare(strict_types=1);

namespace App\Bundles\Template\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TemplateQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRegion, description: '区域', type: 'string'),
        new OA\Property(property: self::getTheme, description: '主题', type: 'string'),
        new OA\Property(property: self::getRemarks, description: '备注', type: 'string'),
    ]
)]
class TemplateQueryRequest extends FormRequest
{
    const string getId = 'id';

    const string getRegion = 'region';

    const string getTheme = 'theme';

    const string getRemarks = 'remarks';

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
