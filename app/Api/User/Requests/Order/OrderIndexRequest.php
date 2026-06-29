<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderIndexRequest',
    properties: [
        new OA\Property(property: self::getStatus, description: '订单状态', type: 'integer', nullable: true),
        new OA\Property(property: self::getKeyword, description: '关键词', type: 'string', nullable: true, maxLength: 100),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class OrderIndexRequest extends FormRequest
{
    const string getStatus = 'status';

    const string getKeyword = 'keyword';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getStatus => 'sometimes|integer',
            self::getKeyword => 'sometimes|string|max:100',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getStatus.'.integer' => '订单状态必须是整数',
            self::getKeyword.'.string' => '关键词必须是字符串',
            self::getKeyword.'.max' => '关键词不能超过100个字符',
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
