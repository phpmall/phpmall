<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\HelpCenter;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalHelpCenterSearchRequest',
    required: [self::getKeyword],
    properties: [
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string'),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class HelpCenterSearchRequest extends FormRequest
{
    const string getKeyword = 'keyword';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getKeyword => 'required|string|max:255',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getKeyword.'.required' => '请输入搜索关键词',
            self::getKeyword.'.max' => '搜索关键词长度不能超过255个字符',
            self::getPage.'.integer' => '页码必须是整数',
            self::getPage.'.min' => '页码不能小于1',
            self::getPerPage.'.integer' => '每页数量必须是整数',
            self::getPerPage.'.max' => '每页数量不能超过100',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
