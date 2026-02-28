<?php

declare(strict_types=1);

namespace App\Bundles\Email\Requests\EmailTemplate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EmailTemplateQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getTemplateId, description: '', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型', type: 'string'),
        new OA\Property(property: self::getTemplateCode, description: '模板代码', type: 'string'),
    ]
)]
class EmailTemplateQueryRequest extends FormRequest
{
    const string getTemplateId = 'templateId';

    const string getType = 'type';

    const string getTemplateCode = 'templateCode';

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
