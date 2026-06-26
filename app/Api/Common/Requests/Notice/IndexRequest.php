<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Notice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonNoticeIndexRequest',
    properties: [
        new OA\Property(property: self::getType, description: '公告类型', type: 'integer', nullable: true),
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string', nullable: true),
        new OA\Property(property: self::getPage, description: '页码', type: 'integer'),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer'),
    ]
)]
class IndexRequest extends FormRequest
{
    const string getType = 'type';

    const string getKeyword = 'keyword';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getType => ['nullable', 'integer'],
            self::getKeyword => ['nullable', 'string', 'max:100'],
            self::getPage => ['nullable', 'integer', 'min:1'],
            self::getPerPage => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getKeyword.'.max' => '关键词不能超过100个字符',
            self::getPage.'.min' => '页码不能小于1',
            self::getPerPage.'.min' => '每页数量不能小于1',
            self::getPerPage.'.max' => '每页数量不能超过100',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
