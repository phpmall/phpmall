<?php

declare(strict_types=1);

namespace App\Api\Shop\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopShopReviewsRequest',
    properties: [
        new OA\Property(property: self::getRating, description: '评分等级:1-5', type: 'integer', nullable: true),
        new OA\Property(property: self::getHasImage, description: '是否有图片:0否,1是', type: 'integer', nullable: true),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class ShopReviewsRequest extends FormRequest
{
    const string getRating = 'rating';

    const string getHasImage = 'has_image';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getRating => 'nullable|integer|min:1|max:5',
            self::getHasImage => 'nullable|integer|in:0,1',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRating.'.integer' => '评分等级必须是整数',
            self::getRating.'.max' => '评分等级最大为5',
            self::getHasImage.'.in' => '是否有图片只能是0或1',
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
