<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Promotion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerPromotionUpdateRequest',
    required: [
        self::getName,
        self::getType,
        self::getRules,
        self::getStartTime,
        self::getEndTime,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '活动名称', type: 'string', maxLength: 100),
        new OA\Property(property: self::getType, description: '活动类型:1满减,2满折,3赠品,4包邮', type: 'integer'),
        new OA\Property(property: self::getRules, description: '活动规则(JSON对象)', type: 'object'),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'string', format: 'date-time'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'string', format: 'date-time'),
        new OA\Property(property: self::getApplicableProductIds, description: '适用商品ID列表', type: 'array', items: new OA\Items(type: 'integer'), nullable: true),
        new OA\Property(property: self::getApplicableCategoryIds, description: '适用分类ID列表', type: 'array', items: new OA\Items(type: 'integer'), nullable: true),
        new OA\Property(property: self::getDescription, description: '活动描述', type: 'string', nullable: true),
        new OA\Property(property: self::getStatus, description: '状态:0禁用,1启用', type: 'integer'),
    ]
)]
class PromotionUpdateRequest extends FormRequest
{
    const string getName = 'name';

    const string getType = 'type';

    const string getRules = 'rules';

    const string getStartTime = 'start_time';

    const string getEndTime = 'end_time';

    const string getApplicableProductIds = 'applicable_product_ids';

    const string getApplicableCategoryIds = 'applicable_category_ids';

    const string getDescription = 'description';

    const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:100'],
            self::getType => ['required', 'integer', 'in:1,2,3,4'],
            self::getRules => ['required', 'array'],
            self::getStartTime => ['required', 'date'],
            self::getEndTime => ['required', 'date', 'after:'.self::getStartTime],
            self::getApplicableProductIds => ['nullable', 'array'],
            self::getApplicableProductIds.'.*' => ['integer', 'min:1'],
            self::getApplicableCategoryIds => ['nullable', 'array'],
            self::getApplicableCategoryIds.'.*' => ['integer', 'min:1'],
            self::getDescription => ['nullable', 'string'],
            self::getStatus => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写活动名称',
            self::getType.'.required' => '请选择活动类型',
            self::getType.'.in' => '活动类型不正确',
            self::getRules.'.required' => '请填写活动规则',
            self::getStartTime.'.required' => '请选择开始时间',
            self::getEndTime.'.required' => '请选择结束时间',
            self::getEndTime.'.after' => '结束时间必须晚于开始时间',
            self::getStatus.'.required' => '请选择状态',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
