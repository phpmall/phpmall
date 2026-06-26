<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerBrandApplyRequest',
    required: [
        self::getName,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '品牌名称', type: 'string'),
        new OA\Property(property: self::getLogo, description: '品牌Logo', type: 'string', nullable: true),
        new OA\Property(property: self::getDescription, description: '品牌描述', type: 'string', nullable: true),
    ]
)]
class BrandApplyRequest extends FormRequest
{
    const string getName = 'name';

    const string getLogo = 'logo';

    const string getDescription = 'description';

    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:100'],
            self::getLogo => ['nullable', 'string', 'max:500'],
            self::getDescription => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写品牌名称',
            self::getName.'.max' => '品牌名称不能超过100个字符',
            self::getLogo.'.max' => '品牌Logo不能超过500个字符',
            self::getDescription.'.max' => '品牌描述不能超过1000个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
