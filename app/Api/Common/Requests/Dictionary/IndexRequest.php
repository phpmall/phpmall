<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Dictionary;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonDictionaryIndexRequest',
    properties: [
        new OA\Property(property: self::getType, description: '字典类型', type: 'string', nullable: true),
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string', nullable: true),
    ]
)]
class IndexRequest extends FormRequest
{
    const string getType = 'type';

    const string getKeyword = 'keyword';

    public function rules(): array
    {
        return [
            self::getType => ['nullable', 'string', 'max:50'],
            self::getKeyword => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.max' => '字典类型不能超过50个字符',
            self::getKeyword.'.max' => '关键词不能超过50个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
