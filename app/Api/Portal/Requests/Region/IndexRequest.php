<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Region;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalRegionIndexRequest',
    properties: [
        new OA\Property(property: self::getParentCode, description: '父级地区编码，顶级传 0', type: 'string', nullable: true),
    ]
)]
class IndexRequest extends FormRequest
{
    public const string getParentCode = 'parent_code';

    public function rules(): array
    {
        return [
            self::getParentCode => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getParentCode.'.string' => '父级地区编码必须是字符串',
            self::getParentCode.'.max' => '父级地区编码不能超过20个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
