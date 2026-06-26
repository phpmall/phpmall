<?php

declare(strict_types=1);

namespace App\Api\Shop\Requests\FreightTemplate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopFreightTemplateCalculateRequest',
    required: [
        self::getTemplateId,
        self::getRegionId,
        self::getWeight,
    ],
    properties: [
        new OA\Property(property: self::getTemplateId, description: '运费模板ID', type: 'integer'),
        new OA\Property(property: self::getRegionId, description: '收货地区ID', type: 'integer'),
        new OA\Property(property: self::getWeight, description: '商品总重量(g)', type: 'integer'),
        new OA\Property(property: self::getQuantity, description: '商品数量', type: 'integer', example: 1),
    ]
)]
class FreightTemplateCalculateRequest extends FormRequest
{
    const string getTemplateId = 'template_id';

    const string getRegionId = 'region_id';

    const string getWeight = 'weight';

    const string getQuantity = 'quantity';

    public function rules(): array
    {
        return [
            self::getTemplateId => 'required|integer|min:1',
            self::getRegionId => 'required|integer|min:1',
            self::getWeight => 'required|integer|min:0',
            self::getQuantity => 'sometimes|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            self::getTemplateId.'.required' => '请选择运费模板',
            self::getTemplateId.'.integer' => '运费模板ID必须是整数',
            self::getRegionId.'.required' => '请选择收货地区',
            self::getRegionId.'.integer' => '地区ID必须是整数',
            self::getWeight.'.required' => '请填写商品重量',
            self::getWeight.'.integer' => '商品重量必须是整数',
            self::getQuantity.'.integer' => '商品数量必须是整数',
            self::getQuantity.'.min' => '商品数量不能小于1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
