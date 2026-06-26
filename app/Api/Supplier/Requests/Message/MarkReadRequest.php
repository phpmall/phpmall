<?php

declare(strict_types=1);

namespace App\Api\Supplier\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierMessageMarkReadRequest',
    properties: [
        new OA\Property(property: self::getIds, description: '消息ID列表(为空则标记全部已读)', type: 'array', items: new OA\Items(type: 'integer'), nullable: true),
    ]
)]
class MarkReadRequest extends FormRequest
{
    const string getIds = 'ids';

    public function rules(): array
    {
        return [
            self::getIds => ['nullable', 'array'],
            self::getIds.'.*' => ['integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getIds.'.array' => 'ID列表必须为数组',
            self::getIds.'.*.integer' => 'ID必须为整数',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
