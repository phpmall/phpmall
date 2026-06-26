<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests\Notice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalNoticeIndexRequest',
    properties: [
        new OA\Property(property: self::getType, description: '公告类型', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class NoticeIndexRequest extends FormRequest
{
    const string getType = 'type';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getType => 'nullable|integer|min:0',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.integer' => '公告类型必须是整数',
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
