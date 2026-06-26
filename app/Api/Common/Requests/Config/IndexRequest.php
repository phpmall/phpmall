<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonConfigIndexRequest',
    properties: [
        new OA\Property(property: self::getGroup, description: '配置分组', type: 'string', nullable: true),
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string', nullable: true),
    ]
)]
class IndexRequest extends FormRequest
{
    const string getGroup = 'group';

    const string getKeyword = 'keyword';

    public function rules(): array
    {
        return [
            self::getGroup => ['nullable', 'string', 'max:50'],
            self::getKeyword => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getGroup.'.max' => '配置分组不能超过50个字符',
            self::getKeyword.'.max' => '关键词不能超过50个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
