<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\ShopDecoration;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerShopDecorationIndexRequest',
    properties: [
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', nullable: true),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', nullable: true),
    ]
)]
class ShopDecorationIndexRequest extends FormRequest
{
    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getPage => ['sometimes', 'nullable', 'integer', 'min:1'],
            self::getPerPage => ['sometimes', 'nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getPage.'.integer' => '页码必须是整数',
            self::getPage.'.min' => '页码不能小于1',
            self::getPerPage.'.integer' => '每页数量必须是整数',
            self::getPerPage.'.min' => '每页数量不能小于1',
            self::getPerPage.'.max' => '每页数量不能超过100',
        ];
    }
}
