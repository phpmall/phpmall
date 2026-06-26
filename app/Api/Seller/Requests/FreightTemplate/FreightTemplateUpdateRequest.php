<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\FreightTemplate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerFreightTemplateUpdateRequest',
    required: [
        self::getName,
        self::getPricingType,
        self::getDefaultFirstUnit,
        self::getDefaultFirstFee,
        self::getDefaultContinueUnit,
        self::getDefaultContinueFee,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '模板名称', type: 'string', maxLength: 50),
        new OA\Property(property: self::getPricingType, description: '计价方式:1按件数,2按重量,3按体积', type: 'integer'),
        new OA\Property(property: self::getIsFreeShipping, description: '是否包邮:0否,1是', type: 'integer'),
        new OA\Property(property: self::getFreeShippingThreshold, description: '包邮门槛金额(分)', type: 'integer', nullable: true),
        new OA\Property(property: self::getDefaultFirstUnit, description: '默认首件/首重/首体积', type: 'integer'),
        new OA\Property(property: self::getDefaultFirstFee, description: '默认首费(分)', type: 'integer'),
        new OA\Property(property: self::getDefaultContinueUnit, description: '默认续件/续重/续体积', type: 'integer'),
        new OA\Property(property: self::getDefaultContinueFee, description: '默认续费(分)', type: 'integer'),
        new OA\Property(
            property: self::getRegionRules,
            description: '区域运费规则',
            type: 'array',
            items: new OA\Items(type: 'object', properties: [
                new OA\Property(property: 'region_ids', type: 'array', items: new OA\Items(type: 'integer')),
                new OA\Property(property: 'region_names', type: 'array', items: new OA\Items(type: 'string')),
                new OA\Property(property: 'first_unit', type: 'integer'),
                new OA\Property(property: 'first_fee', type: 'integer', description: '首费(分)'),
                new OA\Property(property: 'continue_unit', type: 'integer'),
                new OA\Property(property: 'continue_fee', type: 'integer', description: '续费(分)'),
            ])
        ),
        new OA\Property(property: self::getIsDefault, description: '是否默认模板:0否,1是', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0禁用,1启用', type: 'integer'),
    ]
)]
class FreightTemplateUpdateRequest extends FormRequest
{
    const string getName = 'name';

    const string getPricingType = 'pricing_type';

    const string getIsFreeShipping = 'is_free_shipping';

    const string getFreeShippingThreshold = 'free_shipping_threshold';

    const string getDefaultFirstUnit = 'default_first_unit';

    const string getDefaultFirstFee = 'default_first_fee';

    const string getDefaultContinueUnit = 'default_continue_unit';

    const string getDefaultContinueFee = 'default_continue_fee';

    const string getRegionRules = 'region_rules';

    const string getIsDefault = 'is_default';

    const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:50'],
            self::getPricingType => ['required', 'integer', 'in:1,2,3'],
            self::getIsFreeShipping => ['nullable', 'integer', 'in:0,1'],
            self::getFreeShippingThreshold => ['nullable', 'integer', 'min:0'],
            self::getDefaultFirstUnit => ['required', 'integer', 'min:0'],
            self::getDefaultFirstFee => ['required', 'integer', 'min:0'],
            self::getDefaultContinueUnit => ['required', 'integer', 'min:0'],
            self::getDefaultContinueFee => ['required', 'integer', 'min:0'],
            self::getRegionRules => ['nullable', 'array'],
            self::getRegionRules.'.*.region_ids' => ['required_with:'.self::getRegionRules, 'array'],
            self::getRegionRules.'.*.first_unit' => ['required_with:'.self::getRegionRules, 'integer', 'min:0'],
            self::getRegionRules.'.*.first_fee' => ['required_with:'.self::getRegionRules, 'integer', 'min:0'],
            self::getRegionRules.'.*.continue_unit' => ['required_with:'.self::getRegionRules, 'integer', 'min:0'],
            self::getRegionRules.'.*.continue_fee' => ['required_with:'.self::getRegionRules, 'integer', 'min:0'],
            self::getIsDefault => ['nullable', 'integer', 'in:0,1'],
            self::getStatus => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写模板名称',
            self::getName.'.max' => '模板名称不能超过50个字符',
            self::getPricingType.'.required' => '请选择计价方式',
            self::getPricingType.'.in' => '计价方式格式不正确',
            self::getDefaultFirstUnit.'.required' => '请填写首件/首重/首体积',
            self::getDefaultFirstFee.'.required' => '请填写默认首费',
            self::getDefaultContinueUnit.'.required' => '请填写续件/续重/续体积',
            self::getDefaultContinueFee.'.required' => '请填写默认续费',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
