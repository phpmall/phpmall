<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Requests\AdminAction;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminActionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getActionId, description: '', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
    ]
)]
class AdminActionQueryRequest extends FormRequest
{
    const string getActionId = 'actionId';

    const string getParentId = 'parentId';

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
