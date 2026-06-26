<?php

declare(strict_types=1);

namespace App\Api\Shop\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopStoreIndexRequest',
    properties: [
        new OA\Property(property: self::getKeyword, description: '搜索关键词', type: 'string', nullable: true),
        new OA\Property(property: self::getCityId, description: '城市ID', type: 'integer', nullable: true),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class StoreIndexRequest extends FormRequest
{
    const string getKeyword = 'keyword';

    const string getCityId = 'city_id';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getKeyword => 'nullable|string|max:255',
            self::getCityId => 'nullable|integer|min:1',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getKeyword.'.max' => '搜索关键词长度不能超过255个字符',
            self::getCityId.'.integer' => '城市ID必须是整数',
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
